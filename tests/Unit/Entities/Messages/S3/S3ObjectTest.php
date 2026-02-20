<?php

declare(strict_types = 1);

namespace Tests\Unit\Entities\Messages\S3;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\S3\S3Object;
use Tests\TestCase;

/**
 * S3ObjectTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(S3Object::class)]
final class S3ObjectTest extends TestCase
{
    /** @var string Shared S3 object key. */
    private const string REPORT_FILE_KEY = 'files/report.csv';

    /**
     * Returns S3 object values and null optionals.
     *
     * @return void
     */
    #[Test]
    public function itReturnsS3ObjectValuesAndNullOptionals(): void
    {
        $object = new S3Object([
            'key'       => self::REPORT_FILE_KEY,
            'size'      => '42',
            'eTag'      => 'abcd',
            'versionId' => 'v1',
            'sequencer' => '001',
        ]);

        self::assertSame(self::REPORT_FILE_KEY, $object->getKey());
        self::assertSame(42, $object->getSize());
        self::assertSame('abcd', $object->getETag());
        self::assertSame('v1', $object->getVersionId());
        self::assertSame('001', $object->getSequencer());

        $object_without_optionals = new S3Object(['key' => self::REPORT_FILE_KEY]);

        self::assertNull($object_without_optionals->getSize());
        self::assertNull($object_without_optionals->getETag());
        self::assertNull($object_without_optionals->getVersionId());
        self::assertNull($object_without_optionals->getSequencer());
    }
}
