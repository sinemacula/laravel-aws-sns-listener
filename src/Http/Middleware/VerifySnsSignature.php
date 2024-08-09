<?php

namespace SineMacula\Aws\Sns\Http\Middleware;

use Aws\Sns\Exception\InvalidSnsMessageException;
use Aws\Sns\Message;
use Aws\Sns\MessageValidator;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\MessageInterface;
use SineMacula\Aws\Sns\MessageFactory;
use Symfony\Component\HttpFoundation\Response;

/**
 * Verify AWS SNS webhook signature.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class VerifySnsSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Aws\Sns\Exception\InvalidSnsMessageException
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $message = MessageFactory::make(Message::fromRawPostData());
        } catch (Exception $exception) {
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
        return new MessageValidator(function (string $certificate_url) {
            return Cache::rememberForever($certificate_url, function () use ($certificate_url) {
                return Http::get($certificate_url)->body();
            });
        });
    }
}
