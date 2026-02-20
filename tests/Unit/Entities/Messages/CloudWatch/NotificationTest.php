<?php

declare(strict_types = 1);

namespace Tests\Unit\Entities\Messages\CloudWatch;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\CloudWatch\Notification;
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
     * Returns CloudWatch notification values.
     *
     * @return void
     */
    #[Test]
    public function itReturnsCloudwatchNotificationValues(): void
    {
        $payload = [
            'AlarmName'        => 'HighCPU',
            'AlarmDescription' => 'CPU alarm',
            'AWSAccountId'     => 123456789012,
            'NewStateValue'    => 'ALARM',
            'NewStateReason'   => 'Threshold breached',
            'OldStateValue'    => 'OK',
            'StateChangeTime'  => '2026-01-01T00:00:00Z',
            'Region'           => 'EU (Ireland)',
        ];

        $notification = new Notification(AwsSnsMessageBuilder::makeMessage([
            'Message' => json_encode($payload, JSON_THROW_ON_ERROR),
        ]));

        self::assertSame('HighCPU', $notification->getAlarmName());
        self::assertSame('CPU alarm', $notification->getAlarmDescription());
        self::assertSame('123456789012', $notification->getAwsAccountId());
        self::assertSame('ALARM', $notification->getNewStateValue());
        self::assertSame('Threshold breached', $notification->getNewStateReason());
        self::assertSame('OK', $notification->getOldStateValue());
        self::assertSame('2026-01-01T00:00:00+00:00', $notification->getStateChangeTime()->toIso8601String());
        self::assertSame('EU (Ireland)', $notification->getRegion());
    }

    /**
     * Returns null when CloudWatch alarm description is missing.
     *
     * @return void
     */
    #[Test]
    public function itReturnsNullWhenCloudwatchAlarmDescriptionIsMissing(): void
    {
        $payload = [
            'AlarmName'       => 'HighCPU',
            'AWSAccountId'    => '123',
            'NewStateValue'   => 'ALARM',
            'NewStateReason'  => 'x',
            'OldStateValue'   => 'OK',
            'StateChangeTime' => '2026-01-01T00:00:00Z',
            'Region'          => 'eu-west-1',
        ];

        $notification = new Notification(AwsSnsMessageBuilder::makeMessage([
            'Message' => json_encode($payload, JSON_THROW_ON_ERROR),
        ]));

        self::assertNull($notification->getAlarmDescription());
    }
}
