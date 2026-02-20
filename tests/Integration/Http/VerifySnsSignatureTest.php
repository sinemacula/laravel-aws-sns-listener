<?php

declare(strict_types = 1);

namespace Tests\Integration\Http;

use Aws\Sns\Exception\InvalidSnsMessageException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\MessageInterface;
use SineMacula\Aws\Sns\Http\Middleware\VerifySnsSignature;
use Symfony\Component\HttpFoundation\Response;
use Tests\Support\AwsSnsMessageBuilder;
use Tests\TestCase;

/**
 * VerifySnsSignatureTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(VerifySnsSignature::class)]
final class VerifySnsSignatureTest extends TestCase
{
    /** @var string SNS route path. */
    private const string SNS_ROUTE = '/hooks/sns';

    /**
     * Wraps message creation errors.
     *
     * @return void
     */
    #[Test]
    public function itWrapsMessageCreationErrors(): void
    {
        unset($_SERVER['HTTP_X_AMZ_SNS_MESSAGE_TYPE']);

        $middleware = new VerifySnsSignature;

        $this->expectException(InvalidSnsMessageException::class);
        $this->expectExceptionMessage('SNS Message Creation Error: SNS message type header not provided.');

        $middleware->handle(Request::create(self::SNS_ROUTE, 'POST'), static fn (): Response => new Response('ok'));
    }

    /**
     * Validates signature and sets resolved message on request.
     *
     * @return void
     */
    #[Test]
    public function itValidatesSignatureAndSetsResolvedMessageOnTheRequest(): void
    {
        $certificate_url = 'https://sns.us-east-1.amazonaws.com/SimpleNotificationService-valid.pem';
        $signed_payload  = AwsSnsMessageBuilder::makeSignedPayload([
            'Type'           => 'Notification',
            'Message'        => '{"value":"valid"}',
            'SigningCertURL' => $certificate_url,
        ]);

        Http::fake([
            $certificate_url => Http::response($signed_payload['certificate'], 200),
        ]);

        $_SERVER['HTTP_X_AMZ_SNS_MESSAGE_TYPE'] = 'Notification';

        $request    = Request::create(self::SNS_ROUTE, 'POST');
        $middleware = new VerifySnsSignature;

        try {
            $response = $this->withPhpInput(
                json_encode($signed_payload['data'], JSON_THROW_ON_ERROR),
                static function () use ($middleware, $request): Response {
                    return $middleware->handle($request, static function (Request $handled_request): Response {
                        $message = $handled_request->attributes->get('sns_message');
                        self::assertInstanceOf(MessageInterface::class, $message);

                        return new Response('ok', 202);
                    });
                },
            );
        } finally {
            unset($_SERVER['HTTP_X_AMZ_SNS_MESSAGE_TYPE']);
        }

        self::assertSame(202, $response->getStatusCode());
        self::assertSame('ok', $response->getContent());
    }

    /**
     * Wraps signature validation errors.
     *
     * @return void
     */
    #[Test]
    public function itWrapsSignatureValidationErrors(): void
    {
        $certificate_url = 'https://sns.us-east-1.amazonaws.com/SimpleNotificationService-invalid.pem';
        $signed_payload  = AwsSnsMessageBuilder::makeSignedPayload([
            'Type'           => 'Notification',
            'Message'        => '{"value":"invalid"}',
            'SigningCertURL' => $certificate_url,
        ]);
        $signed_payload['data']['Signature'] = base64_encode('invalid-signature');

        Http::fake([
            $certificate_url => Http::response($signed_payload['certificate'], 200),
        ]);

        $_SERVER['HTTP_X_AMZ_SNS_MESSAGE_TYPE'] = 'Notification';

        $middleware = new VerifySnsSignature;

        $this->expectException(InvalidSnsMessageException::class);
        $this->expectExceptionMessage('SNS Message Validation Error: The message signature is invalid.');

        try {
            $this->withPhpInput(
                json_encode($signed_payload['data'], JSON_THROW_ON_ERROR),
                static fn () => $middleware->handle(
                    Request::create(self::SNS_ROUTE, 'POST'),
                    static fn (): Response => new Response('ok'),
                ),
            );
        } finally {
            unset($_SERVER['HTTP_X_AMZ_SNS_MESSAGE_TYPE']);
        }
    }

    /**
     * Throws when certificate fetch does not return an HTTP response.
     *
     * @return void
     */
    #[Test]
    public function itThrowsWhenCertificateFetchDoesNotReturnAnHttpResponse(): void
    {
        $certificate_url = 'https://sns.us-east-1.amazonaws.com/SimpleNotificationService-invalid-response.pem';
        $signed_payload  = AwsSnsMessageBuilder::makeSignedPayload([
            'Type'           => 'Notification',
            'Message'        => '{"value":"invalid-response"}',
            'SigningCertURL' => $certificate_url,
        ]);

        Http::shouldReceive('get')
            ->once()
            ->with($certificate_url)
            ->andReturn('invalid-response');

        $_SERVER['HTTP_X_AMZ_SNS_MESSAGE_TYPE'] = 'Notification';

        $middleware = new VerifySnsSignature;

        $this->expectException(InvalidSnsMessageException::class);
        $this->expectExceptionMessage('SNS certificate request did not return a valid response.');

        try {
            $this->withPhpInput(
                json_encode($signed_payload['data'], JSON_THROW_ON_ERROR),
                static fn () => $middleware->handle(
                    Request::create(self::SNS_ROUTE, 'POST'),
                    static fn (): Response => new Response('ok'),
                ),
            );
        } finally {
            unset($_SERVER['HTTP_X_AMZ_SNS_MESSAGE_TYPE']);
        }
    }

    /**
     * Runs a callback with deterministic php://input contents.
     *
     * @template TReturn
     *
     * @param  string  $input
     * @param  callable(): TReturn  $callback
     * @return TReturn
     */
    private function withPhpInput(string $input, callable $callback): mixed
    {
        $stream_class           = $this->ensurePhpInputStreamWrapper();
        $stream_class::$content = $input;

        stream_wrapper_unregister('php');
        stream_wrapper_register('php', $stream_class);

        try {
            return $callback();
        } finally {
            stream_wrapper_restore('php');
            $stream_class::$content = '';
        }
    }

    /**
     * Ensures the dynamic php://input stream wrapper class exists.
     *
     * @return string
     */
    private function ensurePhpInputStreamWrapper(): string
    {
        $class_name = __NAMESPACE__ . '\PhpInputStreamWrapper';

        if (!class_exists($class_name, false)) {
            $wrapper_class_definition = <<<'PHP'
                namespace Tests\Integration\Http;

                /**
                 * Dynamic php://input stream wrapper for signature tests.
                 *
                 * @author      Ben Carey <bdmc@sinemacula.co.uk>
                 * @copyright   2026 Sine Macula Limited.
                 *
                 * @internal
                 */
                final class PhpInputStreamWrapper
                {
                    /** @var string Stream content buffer. */
                    public static string $content = '';

                    /** @var mixed Stream wrapper context. */
                    public $context;

                    /** @var int Stream read cursor position. */
                    private int $position = 0;

                    public function stream_open(string $path, string $mode, int $options, ?string &$opened_path): bool
                    {
                        $this->position = 0;

                        return true;
                    }

                    public function stream_read(int $count): string
                    {
                        $output = substr(self::$content, $this->position, $count);
                        $output = $output === false ? '' : $output;

                        $this->position += strlen($output);

                        return $output;
                    }

                    public function stream_eof(): bool
                    {
                        return $this->position >= strlen(self::$content);
                    }

                    public function stream_stat(): array
                    {
                        return [];
                    }
                }
                PHP;

            eval($wrapper_class_definition);
        }

        return $class_name;
    }
}
