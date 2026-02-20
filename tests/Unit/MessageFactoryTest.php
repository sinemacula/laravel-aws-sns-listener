<?php

declare(strict_types = 1);

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\CloudWatch\Notification as CloudWatchNotification;
use SineMacula\Aws\Sns\Entities\Messages\S3\Notification as S3Notification;
use SineMacula\Aws\Sns\Entities\Messages\Ses\Notification as SesNotification;
use SineMacula\Aws\Sns\Entities\Messages\SNSNotification;
use SineMacula\Aws\Sns\Entities\Messages\SubscriptionConfirmation;
use SineMacula\Aws\Sns\Entities\Messages\TestNotification;
use SineMacula\Aws\Sns\Exceptions\UnsupportedMessageException;
use SineMacula\Aws\Sns\MessageFactory;
use Tests\Support\AwsSnsMessageBuilder;
use Tests\TestCase;

/**
 * MessageFactoryTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(MessageFactory::class)]
#[CoversClass(UnsupportedMessageException::class)]
final class MessageFactoryTest extends TestCase
{
    /**
     * @return iterable<string, array{0: array<string, mixed>, 1: class-string}>
     */
    public static function messageProvider(): iterable
    {
        yield 'subscription confirmation message' => [
            [
                'Type'    => 'SubscriptionConfirmation',
                'Message' => '{"value":"subscription"}',
            ],
            SubscriptionConfirmation::class,
        ];

        yield 's3 notification message' => [
            [
                'Type'    => 'Notification',
                'Message' => '{"Records":[{"s3":{"bucket":{"name":"uploads"},"object":{"key":"report.csv"}}}]}',
            ],
            S3Notification::class,
        ];

        yield 'ses notification message' => [
            [
                'Type'    => 'Notification',
                'Message' => json_encode([
                    'notificationType' => 'Bounce',
                    'mail'             => [
                        'messageId'        => 'mail',
                        'timestamp'        => '2026-01-01T00:00:00Z',
                        'source'           => 'sender@example.test',
                        'sourceArn'        => 'arn',
                        'sourceIp'         => '127.0.0.1',
                        'sendingAccountId' => '123',
                        'callerIdentity'   => 'caller',
                        'destination'      => [],
                    ],
                ], JSON_THROW_ON_ERROR),
            ],
            SesNotification::class,
        ];

        yield 'cloudwatch notification message' => [
            [
                'Type'    => 'Notification',
                'Message' => '{"AlarmName":"HighCPU","NewStateValue":"ALARM"}',
            ],
            CloudWatchNotification::class,
        ];

        yield 'test notification message' => [
            [
                'Type'    => 'Notification',
                'Message' => '{"Event":"sns:TestEvent"}',
            ],
            TestNotification::class,
        ];

        yield 'generic notification message' => [
            [
                'Type'    => 'Notification',
                'Message' => '{"value":"generic"}',
            ],
            SNSNotification::class,
        ];

        yield 'generic notification with non json payload' => [
            [
                'Type'    => 'Notification',
                'Message' => 'not-json',
            ],
            SNSNotification::class,
        ];
    }

    /**
     * Maps SNS messages to expected entity implementations.
     *
     * @param  array<string, mixed>  $message_data
     * @param  class-string  $expected_class
     * @return void
     */
    #[DataProvider('messageProvider')]
    #[Test]
    public function itMapsSnsMessagesToTheExpectedEntities(array $message_data, string $expected_class): void
    {
        $message = AwsSnsMessageBuilder::makeMessage($message_data);

        $entity = MessageFactory::make($message);

        self::assertInstanceOf($expected_class, $entity);
    }

    /**
     * It throws for unsupported message types.
     *
     * @return void
     */
    #[Test]
    public function itThrowsForUnsupportedMessageTypes(): void
    {
        $message = AwsSnsMessageBuilder::makeMessage([
            'Type'    => 'UnsupportedType',
            'Message' => 'not-json',
        ]);

        $this->expectException(UnsupportedMessageException::class);
        $this->expectExceptionMessage('Unsupported SNS message type: UnsupportedType');

        MessageFactory::make($message);
    }
}
