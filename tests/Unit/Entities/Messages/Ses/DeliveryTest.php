<?php

declare(strict_types = 1);

namespace Tests\Unit\Entities\Messages\Ses;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\Ses\Delivery;
use Tests\TestCase;

/**
 * DeliveryTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(Delivery::class)]
final class DeliveryTest extends TestCase
{
    /** @var string Shared event timestamp. */
    private const string EVENT_TIMESTAMP = '2026-01-01T00:00:00Z';

    /** @var string Shared formatted timestamp. */
    private const string EVENT_TIMESTAMP_ISO = '2026-01-01T00:00:00+00:00';

    /** @var string Shared recipient email. */
    private const string RECIPIENT_EMAIL = 'recipient@example.test';

    /** @var string Shared SMTP response. */
    private const string SMTP_RESPONSE = '250 ok';

    /** @var string Shared remote MTA IP. */
    private const string REMOTE_MTA_IP = '127.0.0.2';

    /**
     * Returns delivery values.
     *
     * @return void
     */
    #[Test]
    public function itReturnsDeliveryValues(): void
    {
        $delivery = new Delivery([
            'name'                 => 'delivery-1',
            'recipients'           => [self::RECIPIENT_EMAIL],
            'timestamp'            => self::EVENT_TIMESTAMP,
            'processingTimeMillis' => '120',
            'reportingMTA'         => 'mta.example.test',
            'smtpResponse'         => self::SMTP_RESPONSE,
            'remoteMtaIp'          => self::REMOTE_MTA_IP,
        ]);

        self::assertSame('delivery-1', $delivery->getName());
        self::assertSame([self::RECIPIENT_EMAIL], $delivery->getRecipients());
        self::assertSame(self::EVENT_TIMESTAMP_ISO, $delivery->getTimestamp()->toIso8601String());
        self::assertSame(120, $delivery->getProcessingTime());
        self::assertSame('mta.example.test', $delivery->getReportingMta());
        self::assertSame(self::SMTP_RESPONSE, $delivery->getSmtpResponse());
        self::assertSame(self::REMOTE_MTA_IP, $delivery->getRemoteMtaIp());
    }
}
