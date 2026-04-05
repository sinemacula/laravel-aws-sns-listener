<?php

declare(strict_types = 1);

namespace Tests\Unit\Events;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\NotificationInterface;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\SNSNotificationInterface;
use SineMacula\Aws\Sns\Events\SNSNotificationReceived;
use Tests\TestCase;

/**
 * SNSNotificationReceivedTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(SNSNotificationReceived::class)]
final class SNSNotificationReceivedTest extends TestCase
{
    /** @var string Invalid notification type message. */
    private const string INVALID_NOTIFICATION_TYPE_MESSAGE = 'Invalid notification type';

    /**
     * Returns typed SNS notification event payload.
     *
     * @return void
     */
    #[Test]
    public function itReturnsTypedSnsNotificationEventPayload(): void
    {
        $notification = self::createStub(SNSNotificationInterface::class);

        $event = new SNSNotificationReceived($notification);

        self::assertSame($notification, $event->getNotification());
    }

    /**
     * Throws for invalid SNS notification type.
     *
     * @return void
     */
    #[Test]
    public function itThrowsForInvalidSnsNotificationType(): void
    {
        $event = new class (self::createStub(SNSNotificationInterface::class)) extends SNSNotificationReceived {
            /**
             * Overwrite notification with a generic type.
             *
             * @param  \SineMacula\Aws\Sns\Entities\Messages\Contracts\NotificationInterface  $notification
             * @return void
             */
            public function forceNotification(NotificationInterface $notification): void
            {
                $this->notification = $notification;
            }
        };

        $event->forceNotification(self::createStub(NotificationInterface::class));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(self::INVALID_NOTIFICATION_TYPE_MESSAGE);

        $event->getNotification();
    }
}
