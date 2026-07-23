<?php

declare(strict_types = 1);

namespace Tests\Unit\Entities\Messages;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\SNSNotification;
use Tests\Support\AwsSnsMessageBuilder;
use Tests\TestCase;

/**
 * SNSNotificationTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(SNSNotification::class)]
final class SNSNotificationTest extends TestCase
{
    /** @var string Ready event payload value. */
    private const string READY_EVENT_PAYLOAD = '{"event":"ready"}';

    /**
     * Returns raw message content for generic SNS notifications.
     *
     * @return void
     */
    #[Test]
    public function itReturnsRawMessageContentForGenericSnsNotifications(): void
    {
        $notification = new SNSNotification(AwsSnsMessageBuilder::makeMessage([
            'Message' => self::READY_EVENT_PAYLOAD,
        ]));

        self::assertSame(self::READY_EVENT_PAYLOAD, $notification->getRawMessage());

        $baseMessage            = AwsSnsMessageBuilder::makeMessage(['Message' => self::READY_EVENT_PAYLOAD]);
        $baseMessage['Message'] = ['not', 'a', 'string'];

        $invalidNotification = new SNSNotification($baseMessage);

        self::assertSame('', $invalidNotification->getRawMessage());
    }
}
