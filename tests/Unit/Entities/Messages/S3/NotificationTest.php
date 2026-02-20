<?php

declare(strict_types = 1);

namespace Tests\Unit\Entities\Messages\S3;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\S3\Notification;
use SineMacula\Aws\Sns\Entities\Messages\S3\Record;
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
     * Maps S3 records and caches the result.
     *
     * @return void
     */
    #[Test]
    public function itMapsS3RecordsAndCachesTheResult(): void
    {
        $payload = [
            'Records' => [
                [
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
                            'key' => self::REPORT_FILE_KEY,
                        ],
                    ],
                ],
                (object) [
                    'eventSource' => self::AWS_EVENT_SOURCE,
                    'awsRegion'   => 'eu-west-1',
                    'eventTime'   => self::EVENT_TIMESTAMP,
                    'eventName'   => 'ObjectRemoved:Delete',
                    's3'          => (object) [
                        'bucket' => (object) [
                            'name' => 'uploads',
                            'arn'  => self::BUCKET_ARN,
                        ],
                        'object' => (object) [
                            'key' => 'files/archive.csv',
                        ],
                    ],
                ],
            ],
        ];

        $notification = new Notification(AwsSnsMessageBuilder::makeMessage([
            'Message' => json_encode($payload, JSON_THROW_ON_ERROR),
        ]));

        $records = $notification->getRecords();

        self::assertCount(2, $records);
        self::assertInstanceOf(Record::class, $records[0]);
        self::assertInstanceOf(Record::class, $records[1]);
        self::assertSame($records, $notification->getRecords());
    }

    /**
     * Returns empty records when records payload is not an array.
     *
     * @return void
     */
    #[Test]
    public function itReturnsEmptyRecordsWhenRecordsPayloadIsNotAnArray(): void
    {
        $notification = new Notification(AwsSnsMessageBuilder::makeMessage([
            'Message' => '{"Records":5}',
        ]));

        self::assertSame([], $notification->getRecords());
    }
}
