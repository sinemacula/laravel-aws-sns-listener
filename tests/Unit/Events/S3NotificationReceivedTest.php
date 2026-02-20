<?php

declare(strict_types = 1);

namespace Tests\Unit\Events;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\NotificationInterface;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\S3NotificationInterface;
use SineMacula\Aws\Sns\Events\S3NotificationReceived;
use Tests\TestCase;

/**
 * S3NotificationReceivedTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(S3NotificationReceived::class)]
final class S3NotificationReceivedTest extends TestCase
{
    /** @var string Invalid notification type message. */
    private const string INVALID_NOTIFICATION_TYPE_MESSAGE = 'Invalid notification type';

    /**
     * Returns typed S3 notification event payload.
     *
     * @return void
     */
    #[Test]
    public function itReturnsTypedS3NotificationEventPayload(): void
    {
        $notification = self::createStub(S3NotificationInterface::class);

        $event = new S3NotificationReceived($notification);

        self::assertSame($notification, $event->getNotification());
    }

    /**
     * Throws for invalid S3 notification type.
     *
     * @return void
     */
    #[Test]
    public function itThrowsForInvalidS3NotificationType(): void
    {
        $event = new class (self::createStub(S3NotificationInterface::class)) extends S3NotificationReceived {
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
