<?php

namespace SineMacula\Aws\Sns\Entities\Messages\Contracts;

/**
 * S3 notification interface.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
interface S3NotificationInterface extends NotificationInterface
{
    /**
     * Return the message records.
     *
     * @return array<int, \SineMacula\Aws\Sns\Entities\Messages\S3\Record>
     */
    public function getRecords(): array;
}
