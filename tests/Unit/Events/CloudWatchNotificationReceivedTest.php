<?php

declare(strict_types = 1);

namespace Tests\Unit\Events;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\CloudWatchNotificationInterface;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\NotificationInterface;
use SineMacula\Aws\Sns\Events\CloudWatchNotificationReceived;
use SineMacula\Aws\Sns\Events\NotificationReceived;
use Tests\TestCase;

/**
 * CloudWatchNotificationReceivedTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(CloudWatchNotificationReceived::class)]
final class CloudWatchNotificationReceivedTest extends TestCase
{
    /** @var string Invalid notification type message. */
    private const string INVALID_NOTIFICATION_TYPE_MESSAGE = 'Invalid notification type';

    /**
     * Returns typed CloudWatch notification event payload.
     *
     * @return void
     */
    #[Test]
    public function itReturnsTypedCloudwatchNotificationEventPayload(): void
    {
        $notification = self::createStub(CloudWatchNotificationInterface::class);

        $event = new CloudWatchNotificationReceived($notification);

        self::assertSame($notification, $event->getNotification());
    }

    /**
     * Throws for invalid CloudWatch notification type.
     *
     * @return void
     *
     * @SuppressWarnings("php:S3011")
     */
    #[Test]
    public function itThrowsForInvalidCloudwatchNotificationType(): void
    {
        $event = new CloudWatchNotificationReceived(self::createStub(CloudWatchNotificationInterface::class));

        $property = new \ReflectionProperty(NotificationReceived::class, 'notification');
        $property->setValue($event, self::createStub(NotificationInterface::class));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(self::INVALID_NOTIFICATION_TYPE_MESSAGE);

        $event->getNotification();
    }
}
