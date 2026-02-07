<?php

namespace Tests\Unit;

use Aws\Sns\Message;
use PHPUnit\Framework\TestCase;
use SineMacula\Aws\Sns\Entities\Messages\S3\Notification as S3Notification;
use SineMacula\Aws\Sns\Entities\Messages\SNSNotification;
use SineMacula\Aws\Sns\MessageFactory;

/**
 * MessageFactory unit tests.
 *
 * @internal
 *
 * @covers \SineMacula\Aws\Sns\MessageFactory
 */
class MessageFactoryTest extends TestCase
{
    public function test_it_returns_generic_sns_notification_for_plain_text_notification(): void
    {
        $message = MessageFactory::make($this->makeMessage('plain text notification payload'));

        self::assertInstanceOf(SNSNotification::class, $message);
        self::assertSame('plain text notification payload', $message->getRawMessage());
        self::assertSame('plain text notification payload', $message->getMessage()->raw);
    }

    public function test_it_prefers_specialised_notification_types_over_generic_type(): void
    {
        $message = MessageFactory::make($this->makeMessage(json_encode([
            'Records' => [
                ['s3' => ['bucket' => ['name' => 'bucket-name']]],
            ],
        ]) ?: ''));

        self::assertInstanceOf(S3Notification::class, $message);
    }

    private function makeMessage(string $payload): Message
    {
        return new Message([
            'Type' => 'Notification',
            'MessageId' => '11111111-2222-3333-4444-555555555555',
            'TopicArn' => 'arn:aws:sns:eu-west-1:123456789012:example',
            'Subject' => 'Test',
            'Message' => $payload,
            'Timestamp' => '2024-01-01T00:00:00.000Z',
            'SignatureVersion' => '1',
            'Signature' => 'signature',
            'SigningCertURL' => 'https://sns.eu-west-1.amazonaws.com/SimpleNotificationService.pem',
            'UnsubscribeURL' => 'https://sns.eu-west-1.amazonaws.com/?Action=Unsubscribe',
        ]);
    }
}
