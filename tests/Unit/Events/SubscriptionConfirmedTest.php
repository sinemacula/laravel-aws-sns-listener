<?php

declare(strict_types = 1);

namespace Tests\Unit\Events;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\SubscriptionConfirmationInterface;
use SineMacula\Aws\Sns\Events\SubscriptionConfirmed;
use Tests\TestCase;

/**
 * SubscriptionConfirmedTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(SubscriptionConfirmed::class)]
final class SubscriptionConfirmedTest extends TestCase
{
    /**
     * Returns message from subscription confirmed event.
     *
     * @return void
     */
    #[Test]
    public function itReturnsSubscriptionMessageFromSubscriptionConfirmedEvent(): void
    {
        $message = self::createStub(SubscriptionConfirmationInterface::class);

        $event = new SubscriptionConfirmed($message);

        self::assertSame($message, $event->getMessage());
    }
}
