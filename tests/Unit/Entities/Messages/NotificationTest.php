<?php

declare(strict_types = 1);

namespace Tests\Unit\Entities\Messages;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\Notification;
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
    /**
     * Returns unsubscribe URL only when it is a string.
     *
     * @return void
     */
    #[Test]
    public function itReturnsUnsubscribeUrlOnlyWhenItIsAString(): void
    {
        $notification = new class (AwsSnsMessageBuilder::makeMessage(['UnsubscribeURL' => 'https://example.test/unsubscribe'])) extends Notification {};

        self::assertSame('https://example.test/unsubscribe', $notification->getUnsubscribeUrl());

        $invalidNotification = new class (AwsSnsMessageBuilder::makeMessage(['UnsubscribeURL' => ['invalid']])) extends Notification {};

        self::assertNull($invalidNotification->getUnsubscribeUrl());
    }
}
