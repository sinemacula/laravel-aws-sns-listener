<?php

declare(strict_types = 1);

namespace Tests\Unit\Events;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\NotificationInterface;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\SesNotificationInterface;
use SineMacula\Aws\Sns\Events\SesNotificationReceived;
use Tests\TestCase;

/**
 * SesNotificationReceivedTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(SesNotificationReceived::class)]
final class SesNotificationReceivedTest extends TestCase
{
    /** @var string Invalid notification type message. */
    private const string INVALID_NOTIFICATION_TYPE_MESSAGE = 'Invalid notification type';

    /**
     * Returns typed SES notification event payload.
     *
     * @return void
     */
    #[Test]
    public function itReturnsTypedSesNotificationEventPayload(): void
    {
        $notification = self::createStub(SesNotificationInterface::class);

        $event = new SesNotificationReceived($notification);

        self::assertSame($notification, $event->getNotification());
    }

    /**
     * Throws for invalid SES notification type.
     *
     * @return void
     */
    #[Test]
    public function itThrowsForInvalidSesNotificationType(): void
    {
        $event = new class (self::createStub(SesNotificationInterface::class)) extends SesNotificationReceived {
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
