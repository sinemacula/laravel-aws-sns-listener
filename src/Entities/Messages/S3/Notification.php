<?php

namespace SineMacula\Aws\Sns\Entities\Messages\S3;

use SineMacula\Aws\Sns\Entities\Messages\Contracts\S3NotificationInterface;
use SineMacula\Aws\Sns\Entities\Messages\Notification as BaseNotification;
use stdClass;

/**
 * AWS SNS S3 notification instance.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class Notification extends BaseNotification implements S3NotificationInterface
{
    /** @var array */
    protected array $records;

    /**
     * Return the message records.
     *
     * @return array<int, \SineMacula\Aws\Sns\Entities\Messages\S3\Record>
     */
    public function getRecords(): array
    {
        return $this->records ??= array_map(fn (stdClass $record) => new Record($record), $this->getMessage()->Records ?? []);
    }
}
