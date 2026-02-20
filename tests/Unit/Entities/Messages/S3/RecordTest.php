<?php

declare(strict_types = 1);

namespace Tests\Unit\Entities\Messages\S3;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\S3\Record;
use Tests\TestCase;

/**
 * RecordTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(Record::class)]
final class RecordTest extends TestCase
{
    /** @var string Shared S3 bucket ARN. */
    private const string BUCKET_ARN = 'arn:aws:s3:::uploads';

    /** @var string Shared S3 object key. */
    private const string REPORT_FILE_KEY = 'files/report.csv';

    /** @var string Shared AWS event source. */
    private const string AWS_EVENT_SOURCE = 'aws:s3';

    /** @var string Shared event timestamp. */
    private const string EVENT_TIMESTAMP = '2026-01-01T00:00:00Z';

    /** @var string Shared object-created event name. */
    private const string OBJECT_CREATED_EVENT = 'ObjectCreated:Put';

    /**
     * Returns record values and caches nested entities.
     *
     * @return void
     */
    #[Test]
    public function itReturnsRecordValuesAndCachesNestedEntities(): void
    {
        $record = new Record([
            'eventSource' => self::AWS_EVENT_SOURCE,
            'awsRegion'   => 'eu-west-1',
            'eventTime'   => self::EVENT_TIMESTAMP,
            'eventName'   => self::OBJECT_CREATED_EVENT,
            's3'          => [
                'bucket' => [
                    'name' => 'uploads',
                    'arn'  => self::BUCKET_ARN,
                ],
                'object' => [
                    'key'  => self::REPORT_FILE_KEY,
                    'size' => 42,
                ],
            ],
        ]);

        self::assertSame(self::AWS_EVENT_SOURCE, $record->getEventSource());
        self::assertSame('eu-west-1', $record->getRegion());
        self::assertSame(self::OBJECT_CREATED_EVENT, $record->getEventName());
        self::assertSame('2026-01-01T00:00:00+00:00', $record->getEventTime()->toIso8601String());

        $bucket = $record->getBucket();
        $object = $record->getObject();

        self::assertSame('uploads', $bucket->getName());
        self::assertSame(self::REPORT_FILE_KEY, $object->getKey());
        self::assertSame($bucket, $record->getBucket());
        self::assertSame($object, $record->getObject());
    }
}
