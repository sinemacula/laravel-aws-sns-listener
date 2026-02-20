<?php

namespace SineMacula\Aws\Sns\Http\Middleware;

use Aws\Sns\Exception\InvalidSnsMessageException;
use Aws\Sns\Message;
use Aws\Sns\MessageValidator;
use Illuminate\Http\Client\Response as HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\MessageInterface;
use SineMacula\Aws\Sns\MessageFactory;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Verify AWS SNS webhook signature.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 */
class VerifySnsSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): \Symfony\Component\HttpFoundation\Response  $next
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Aws\Sns\Exception\InvalidSnsMessageException
     */
    public function handle(Request $request, \Closure $next): SymfonyResponse
    {
        try {
            $message = MessageFactory::make(Message::fromRawPostData());
        } catch (\Exception $exception) {
            throw new InvalidSnsMessageException('SNS Message Creation Error: ' . $exception->getMessage(), 0, $exception);
        }

        $this->validateSnsMessageSignature($message);

        $request->attributes->set('sns_message', $message);

        return $next($request);
    }

    /**
     * Validate the given SNS message.
     *
     * @param  \SineMacula\Aws\Sns\Entities\Messages\Contracts\MessageInterface  $message
     * @return void
     *
     * @throws \Aws\Sns\Exception\InvalidSnsMessageException
     */
    private function validateSnsMessageSignature(MessageInterface $message): void
    {
        $validator = $this->resolveValidator();

        try {
            $validator->validate($message->getBaseMessage());
        } catch (InvalidSnsMessageException $exception) {
            throw new InvalidSnsMessageException('SNS Message Validation Error: ' . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Resolve the message validator.
     *
     * @return \Aws\Sns\MessageValidator
     */
    private function resolveValidator(): MessageValidator
    {
        return new MessageValidator(fn (string $certificate_url) => Cache::rememberForever($certificate_url, fn () => $this->fetchCertificateBody($certificate_url)));
    }

    /**
     * Fetch and return the certificate body.
     *
     * @param  string  $certificate_url
     * @return string
     */
    private function fetchCertificateBody(string $certificate_url): string
    {
        $response = Http::get($certificate_url);

        if (!$response instanceof HttpResponse) {
            throw new InvalidSnsMessageException('SNS certificate request did not return a valid response.');
        }

        return $response->body();
    }
}
