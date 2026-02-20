<?php

declare(strict_types = 1);

namespace Tests\Unit\Entities\Messages\S3;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\S3\Bucket;
use Tests\TestCase;

/**
 * BucketTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(Bucket::class)]
final class BucketTest extends TestCase
{
    /**
     * Returns bucket values.
     *
     * @return void
     */
    #[Test]
    public function itReturnsBucketValues(): void
    {
        $bucket = new Bucket([
            'name' => 'uploads',
            'arn'  => 'arn:aws:s3:::uploads',
        ]);

        self::assertSame('uploads', $bucket->getName());
        self::assertSame('arn:aws:s3:::uploads', $bucket->getArn());
    }
}
