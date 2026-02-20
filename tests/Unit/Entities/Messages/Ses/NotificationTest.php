<?php

declare(strict_types = 1);

namespace Tests\Unit\Entities\Messages\Ses;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\Ses\Bounce;
use SineMacula\Aws\Sns\Entities\Messages\Ses\Complaint;
use SineMacula\Aws\Sns\Entities\Messages\Ses\Delivery;
use SineMacula\Aws\Sns\Entities\Messages\Ses\Mail;
use SineMacula\Aws\Sns\Entities\Messages\Ses\Notification;
use Tests\Support\AwsSnsMessageBuilder;
use Tests\TestCase;

/**
 * NotificationTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(Notification::class)]
final class NotificationTest extends TestCase
{
    /** @var string Shared event timestamp. */
    private const string EVENT_TIMESTAMP = '2026-01-01T00:00:00Z';

    /** @var string Shared sender email. */
    private const string SENDER_EMAIL = 'sender@example.test';

    /** @var string Shared recipient email. */
    private const string RECIPIENT_EMAIL = 'recipient@example.test';

    /** @var string Shared source IP. */
    private const string SOURCE_IP = '127.0.0.1';

    /** @var string Shared SMTP response. */
    private const string SMTP_RESPONSE = '250 ok';

    /** @var string Shared remote MTA IP. */
    private const string REMOTE_MTA_IP = '127.0.0.2';

    /** @var string Shared bounce remote MTA IP. */
    private const string BOUNCE_REMOTE_MTA_IP = '127.0.0.3';

    /**
     * Returns SES notification sections and caches them.
     *
     * @return void
     */
    #[Test]
    public function itReturnsSesNotificationSectionsAndCachesThem(): void
    {
        $payload = [
            'notificationType' => 'Bounce',
            'delivery'         => [
                'name'                 => 'delivery-1',
                'recipients'           => [self::RECIPIENT_EMAIL],
                'timestamp'            => self::EVENT_TIMESTAMP,
                'processingTimeMillis' => 100,
                'reportingMTA'         => 'mta.example.test',
                'smtpResponse'         => self::SMTP_RESPONSE,
                'remoteMtaIp'          => self::REMOTE_MTA_IP,
            ],
            'bounce' => [
                'bounceType'        => 'Permanent',
                'bounceSubType'     => 'General',
                'bouncedRecipients' => [],
                'timestamp'         => self::EVENT_TIMESTAMP,
                'feedbackId'        => 'feedback-1',
                'remoteMtaIp'       => self::BOUNCE_REMOTE_MTA_IP,
            ],
            'complaint' => [
                'complainedRecipients' => [],
                'timestamp'            => self::EVENT_TIMESTAMP,
                'feedbackId'           => 'complaint-1',
            ],
            'mail' => [
                'messageId'        => 'mail-1',
                'timestamp'        => self::EVENT_TIMESTAMP,
                'source'           => self::SENDER_EMAIL,
                'sourceArn'        => 'arn',
                'sourceIp'         => self::SOURCE_IP,
                'sendingAccountId' => '123',
                'callerIdentity'   => 'caller',
                'destination'      => [],
            ],
        ];

        $notification = new Notification(AwsSnsMessageBuilder::makeMessage([
            'Message' => json_encode($payload, JSON_THROW_ON_ERROR),
        ]));

        self::assertSame('Bounce', $notification->getNotificationType());
        self::assertInstanceOf(Delivery::class, $notification->getDelivery());
        self::assertInstanceOf(Bounce::class, $notification->getBounce());
        self::assertInstanceOf(Complaint::class, $notification->getComplaint());
        self::assertInstanceOf(Mail::class, $notification->getMail());
        self::assertSame($notification->getDelivery(), $notification->getDelivery());
        self::assertSame($notification->getBounce(), $notification->getBounce());
        self::assertSame($notification->getComplaint(), $notification->getComplaint());
        self::assertSame($notification->getMail(), $notification->getMail());
    }

    /**
     * Returns null for missing optional SES sections.
     *
     * @return void
     */
    #[Test]
    public function itReturnsNullForMissingOptionalSesSections(): void
    {
        $payload = [
            'notificationType' => 'Delivery',
            'delivery'         => null,
            'bounce'           => null,
            'complaint'        => null,
            'mail'             => [
                'messageId'        => 'mail-1',
                'timestamp'        => self::EVENT_TIMESTAMP,
                'source'           => self::SENDER_EMAIL,
                'sourceArn'        => 'arn',
                'sourceIp'         => self::SOURCE_IP,
                'sendingAccountId' => '123',
                'callerIdentity'   => 'caller',
                'destination'      => [],
            ],
        ];

        $notification = new Notification(AwsSnsMessageBuilder::makeMessage([
            'Message' => json_encode($payload, JSON_THROW_ON_ERROR),
        ]));

        self::assertNull($notification->getDelivery());
        self::assertNull($notification->getBounce());
        self::assertNull($notification->getComplaint());
        self::assertInstanceOf(Mail::class, $notification->getMail());
    }
}
