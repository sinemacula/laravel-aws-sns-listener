<?php

declare(strict_types = 1);

namespace Tests\Unit\Events;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\NotificationInterface;
use SineMacula\Aws\Sns\Events\NotificationReceived;
use Tests\TestCase;

/**
 * NotificationReceivedTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(NotificationReceived::class)]
final class NotificationReceivedTest extends TestCase
{
    /**
     * Returns notification from base event.
     *
     * @return void
     */
    #[Test]
    public function itReturnsNotificationFromBaseEvent(): void
    {
        $notification = self::createStub(NotificationInterface::class);

        $event = new NotificationReceived($notification);

        self::assertSame($notification, $event->getNotification());
    }
}
