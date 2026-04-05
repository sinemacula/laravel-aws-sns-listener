<?php

declare(strict_types = 1);

namespace Tests\Unit\Entities\Messages;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\SubscriptionConfirmation;
use Tests\Support\AwsSnsMessageBuilder;
use Tests\TestCase;

/**
 * SubscriptionConfirmationTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(SubscriptionConfirmation::class)]
final class SubscriptionConfirmationTest extends TestCase
{
    /** @var string Subscription confirmation URL. */
    private const string SUBSCRIBE_URL = 'https://example.test/subscribe';

    /**
     * Returns subscribe URL only when it is a string.
     *
     * @return void
     */
    #[Test]
    public function itReturnsSubscribeUrlOnlyWhenItIsAString(): void
    {
        $message = new SubscriptionConfirmation(AwsSnsMessageBuilder::makeMessage([
            'Type'         => 'SubscriptionConfirmation',
            'SubscribeURL' => self::SUBSCRIBE_URL,
        ]));

        self::assertSame(self::SUBSCRIBE_URL, $message->getSubscribeUrl());

        $invalid_message = AwsSnsMessageBuilder::makeMessage([
            'Type'         => 'SubscriptionConfirmation',
            'SubscribeURL' => self::SUBSCRIBE_URL,
        ]);
        $invalid_message['SubscribeURL'] = ['invalid'];

        $invalid_subscription = new SubscriptionConfirmation($invalid_message);

        self::assertSame('', $invalid_subscription->getSubscribeUrl());
    }
}
