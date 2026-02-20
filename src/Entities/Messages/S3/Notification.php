<?php

namespace SineMacula\Aws\Sns\Entities\Messages\S3;

use SineMacula\Aws\Sns\Entities\Messages\Contracts\S3NotificationInterface;
use SineMacula\Aws\Sns\Entities\Messages\Notification as BaseNotification;

/**
 * AWS SNS S3 notification instance.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 */
class Notification extends BaseNotification implements S3NotificationInterface
{
    /** @var array<int, \SineMacula\Aws\Sns\Entities\Messages\S3\Record>|null */
    protected ?array $records = null;

    /**
     * Return the message records.
     *
     * @return array<int, \SineMacula\Aws\Sns\Entities\Messages\S3\Record>
     */
    public function getRecords(): array
    {
        $records = $this->getMessage()->Records ?? [];
        $records = is_array($records) ? $records : [];

        return $this->records ??= array_map(static fn (array|\stdClass|null $record): Record => new Record($record), $records);
    }
}
