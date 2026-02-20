<?php

declare(strict_types = 1);

namespace Tests\Unit\Entities\Messages;

use Aws\Sns\Message as AwsMessage;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\Message;
use Tests\Support\AwsSnsMessageBuilder;
use Tests\TestCase;

/**
 * MessageTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(Message::class)]
final class MessageTest extends TestCase
{
    /**
     * Returns message metadata and decoded payload values.
     *
     * @return void
     */
    #[Test]
    public function itReturnsMessageMetadataAndDecodedPayloadValues(): void
    {
        $base_message = $this->makeAwsMessage([
            'Message'           => '{"foo":"bar"}',
            'MessageAttributes' => [
                'priority' => ['Type' => 'String', 'Value' => 'high'],
            ],
        ]);

        $message = new class ($base_message) extends Message {};

        self::assertSame($base_message, $message->getBaseMessage());
        self::assertSame('12345678-1234-1234-1234-123456789012', $message->getId());
        self::assertSame('Notification', $message->getType());
        self::assertSame('arn:aws:sns:us-east-1:123456789012:test-topic', $message->getTopic());
        self::assertInstanceOf(Carbon::class, $message->getTimestamp());
        self::assertSame('2026-01-01T00:00:00+00:00', $message->getTimestamp()->toIso8601String());
        self::assertSame('bar', $message->getMessage()->foo);
        self::assertSame('1', $message->getSignatureVersion());
        self::assertNotSame('', $message->getSignature());
        self::assertSame(
            'https://sns.us-east-1.amazonaws.com/SimpleNotificationService-test.pem',
            $message->getSigningCertificateUrl(),
        );

        $attributes = $message->getAttributes();
        self::assertIsArray($attributes);
        self::assertArrayHasKey('priority', $attributes);
        self::assertInstanceOf(\stdClass::class, $attributes['priority']);
        self::assertSame('high', $attributes['priority']->Value);
    }

    /**
     * Wraps non-JSON message payload as raw content.
     *
     * @return void
     */
    #[Test]
    public function itWrapsNonJsonMessagePayloadAsRawContent(): void
    {
        $message = new class ($this->makeAwsMessage(['Message' => 'not-json'])) extends Message {};

        self::assertSame('not-json', $message->getMessage()->raw);
    }

    /**
     * Returns null when message attributes are not normalizable.
     *
     * @return void
     */
    #[Test]
    public function itReturnsNullWhenMessageAttributesAreNotNormalizable(): void
    {
        $message = new class ($this->makeAwsMessage(['MessageAttributes' => 'invalid'])) extends Message {};

        self::assertNull($message->getAttributes());
    }

    /**
     * Normalizes stdClass message attributes.
     *
     * @return void
     */
    #[Test]
    public function itNormalizesStdClassMessageAttributes(): void
    {
        $message_attributes = (object) [
            'priority' => (object) ['Type' => 'String', 'Value' => 'high'],
        ];

        $message = new class ($this->makeAwsMessage(['MessageAttributes' => $message_attributes])) extends Message {};

        $attributes = $message->getAttributes();

        self::assertIsArray($attributes);
        self::assertArrayHasKey('priority', $attributes);
        self::assertInstanceOf(\stdClass::class, $attributes['priority']);
        self::assertSame('high', $attributes['priority']->Value);
    }

    /**
     * Builds an AWS SNS message instance.
     *
     * @param  array<string, mixed>  $overrides
     * @return \Aws\Sns\Message
     */
    private function makeAwsMessage(array $overrides = []): AwsMessage
    {
        return AwsSnsMessageBuilder::makeMessage($overrides);
    }
}
