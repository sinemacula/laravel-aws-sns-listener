<?php

declare(strict_types = 1);

namespace Tests\Unit\Entities\Messages\Ses;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\Ses\Complaint;
use Tests\TestCase;

/**
 * ComplaintTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(Complaint::class)]
final class ComplaintTest extends TestCase
{
    /** @var string Shared event timestamp. */
    private const string EVENT_TIMESTAMP = '2026-01-01T00:00:00Z';

    /** @var string Shared formatted timestamp. */
    private const string EVENT_TIMESTAMP_ISO = '2026-01-01T00:00:00+00:00';

    /** @var string Shared recipient email. */
    private const string RECIPIENT_EMAIL = 'recipient@example.test';

    /**
     * Returns complaint values with optional arrival date.
     *
     * @return void
     */
    #[Test]
    public function itReturnsComplaintValuesWithOptionalArrivalDate(): void
    {
        $complaint = new Complaint([
            'userAgent'             => 'Amazon SES',
            'complainedRecipients'  => [(object) ['emailAddress' => self::RECIPIENT_EMAIL]],
            'complaintFeedbackType' => 'abuse',
            'arrivalDate'           => self::EVENT_TIMESTAMP,
            'timestamp'             => self::EVENT_TIMESTAMP,
            'feedbackId'            => 'complaint-1',
        ]);

        self::assertSame('Amazon SES', $complaint->getUserAgent());
        self::assertCount(1, $complaint->getComplainedRecipients());
        self::assertSame('abuse', $complaint->getFeedbackType());
        self::assertSame(self::EVENT_TIMESTAMP_ISO, $complaint->getArrivalDate()?->toIso8601String());
        self::assertSame(self::EVENT_TIMESTAMP_ISO, $complaint->getTimestamp()->toIso8601String());
        self::assertSame('complaint-1', $complaint->getFeedbackId());

        $complaintWithoutOptionals = new Complaint([
            'complainedRecipients' => [],
            'arrivalDate'          => null,
            'timestamp'            => self::EVENT_TIMESTAMP,
            'feedbackId'           => 'complaint-2',
        ]);

        self::assertNull($complaintWithoutOptionals->getUserAgent());
        self::assertNull($complaintWithoutOptionals->getFeedbackType());
        self::assertNull($complaintWithoutOptionals->getArrivalDate());
    }
}
