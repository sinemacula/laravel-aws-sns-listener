<?php

declare(strict_types = 1);

namespace Tests\Unit\Entities\Messages;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\TestNotification;
use Tests\Support\AwsSnsMessageBuilder;
use Tests\TestCase;

/**
 * TestNotificationTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(TestNotification::class)]
final class TestNotificationTest extends TestCase
{
    /**
     * Instantiates test notification messages.
     *
     * @return void
     */
    #[Test]
    public function itInstantiatesTestNotificationMessages(): void
    {
        $notification = new TestNotification(AwsSnsMessageBuilder::makeMessage([
            'Message' => '{"Event":"sns:TestEvent"}',
        ]));

        self::assertSame('https://sns.us-east-1.amazonaws.com/unsubscribe', $notification->getUnsubscribeUrl());
    }
}
