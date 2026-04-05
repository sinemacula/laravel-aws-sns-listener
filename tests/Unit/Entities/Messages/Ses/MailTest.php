<?php

declare(strict_types = 1);

namespace Tests\Unit\Entities\Messages\Ses;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\Ses\Mail;
use Tests\TestCase;

/**
 * MailTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(Mail::class)]
final class MailTest extends TestCase
{
    /** @var string Shared event timestamp. */
    private const string EVENT_TIMESTAMP = '2026-01-01T00:00:00Z';

    /** @var string Shared formatted timestamp. */
    private const string EVENT_TIMESTAMP_ISO = '2026-01-01T00:00:00+00:00';

    /** @var string Shared sender email. */
    private const string SENDER_EMAIL = 'sender@example.test';

    /** @var string Shared recipient email. */
    private const string RECIPIENT_EMAIL = 'recipient@example.test';

    /** @var string Shared source IP. */
    private const string SOURCE_IP = '127.0.0.1';

    /**
     * Returns mail values and optional fields.
     *
     * @return void
     */
    #[Test]
    public function itReturnsMailValuesAndOptionalFields(): void
    {
        $mail = new Mail([
            'messageId'        => 'mail-1',
            'timestamp'        => self::EVENT_TIMESTAMP,
            'source'           => self::SENDER_EMAIL,
            'sourceArn'        => 'arn:aws:ses:eu-west-1:123:identity/sender@example.test',
            'sourceIp'         => self::SOURCE_IP,
            'sendingAccountId' => '123456789012',
            'callerIdentity'   => 'caller',
            'destination'      => [self::RECIPIENT_EMAIL],
            'headersTruncated' => true,
            'headers'          => ['X-Test' => 'yes'],
            'commonHeaders'    => ['subject' => 'Hello'],
        ]);

        self::assertSame('mail-1', $mail->getId());
        self::assertSame(self::EVENT_TIMESTAMP_ISO, $mail->getTimestamp()->toIso8601String());
        self::assertSame(self::SENDER_EMAIL, $mail->getSource());
        self::assertSame('arn:aws:ses:eu-west-1:123:identity/sender@example.test', $mail->getSourceArn());
        self::assertSame(self::SOURCE_IP, $mail->getSourceIp());
        self::assertSame('123456789012', $mail->getSendingAccountId());
        self::assertSame('caller', $mail->getCallerIdentity());
        self::assertSame([self::RECIPIENT_EMAIL], $mail->getDestinations());
        self::assertTrue($mail->areHeadersTruncated());
        self::assertSame(['X-Test' => 'yes'], $mail->getHeaders());
        self::assertSame(['subject' => 'Hello'], $mail->getCommonHeaders());

        $minimal_mail = new Mail([
            'messageId'        => 'mail-2',
            'timestamp'        => self::EVENT_TIMESTAMP,
            'source'           => self::SENDER_EMAIL,
            'sourceArn'        => 'arn',
            'sourceIp'         => self::SOURCE_IP,
            'sendingAccountId' => '123',
            'callerIdentity'   => 'caller',
            'destination'      => [],
        ]);

        self::assertFalse($minimal_mail->areHeadersTruncated());
        self::assertNull($minimal_mail->getHeaders());
        self::assertNull($minimal_mail->getCommonHeaders());
    }
}
