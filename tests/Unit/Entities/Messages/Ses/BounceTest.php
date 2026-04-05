<?php

declare(strict_types = 1);

namespace Tests\Unit\Entities\Messages\Ses;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\Ses\Bounce;
use Tests\TestCase;

/**
 * BounceTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(Bounce::class)]
final class BounceTest extends TestCase
{
    /** @var string Shared event timestamp. */
    private const string EVENT_TIMESTAMP = '2026-01-01T00:00:00Z';

    /** @var string Shared formatted timestamp. */
    private const string EVENT_TIMESTAMP_ISO = '2026-01-01T00:00:00+00:00';

    /** @var string Shared recipient email. */
    private const string RECIPIENT_EMAIL = 'recipient@example.test';

    /** @var string Shared bounce remote MTA IP. */
    private const string BOUNCE_REMOTE_MTA_IP = '127.0.0.3';

    /**
     * Returns bounce values and optional reporting MTA.
     *
     * @return void
     */
    #[Test]
    public function itReturnsBounceValuesAndOptionalReportingMta(): void
    {
        $bounce = new Bounce([
            'bounceType'        => 'Permanent',
            'bounceSubType'     => 'General',
            'bouncedRecipients' => [(object) ['emailAddress' => self::RECIPIENT_EMAIL]],
            'timestamp'         => self::EVENT_TIMESTAMP,
            'feedbackId'        => 'feedback-1',
            'reportingMTA'      => 'mta.example.test',
            'remoteMtaIp'       => self::BOUNCE_REMOTE_MTA_IP,
        ]);

        self::assertSame('Permanent', $bounce->getType());
        self::assertSame('General', $bounce->getSubtype());
        self::assertCount(1, $bounce->getBouncedRecipients());
        self::assertSame(self::EVENT_TIMESTAMP_ISO, $bounce->getTimestamp()->toIso8601String());
        self::assertSame('feedback-1', $bounce->getFeedbackId());
        self::assertSame('mta.example.test', $bounce->getReportingMta());
        self::assertSame(self::BOUNCE_REMOTE_MTA_IP, $bounce->getRemoteMtaIp());

        $bounce_without_reporting_mta = new Bounce([
            'bounceType'        => 'Permanent',
            'bounceSubType'     => 'General',
            'bouncedRecipients' => [],
            'timestamp'         => self::EVENT_TIMESTAMP,
            'feedbackId'        => 'feedback-2',
            'remoteMtaIp'       => '127.0.0.4',
        ]);

        self::assertNull($bounce_without_reporting_mta->getReportingMta());
    }
}
