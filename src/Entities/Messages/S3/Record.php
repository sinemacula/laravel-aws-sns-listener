<?php

declare(strict_types = 1);

namespace SineMacula\Aws\Sns\Entities\Messages\S3;

use Carbon\Carbon;
use SineMacula\Aws\Sns\Entities\Entity;

/**
 * AWS S3 notification record instance.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 */
final class Record extends Entity
{
    /** @var \SineMacula\Aws\Sns\Entities\Messages\S3\Bucket|null Bucket value. */
    protected ?Bucket $bucket = null;

    /** @var \SineMacula\Aws\Sns\Entities\Messages\S3\S3Object|null Object value. */
    protected ?S3Object $object = null;

    /**
     * Return the event source.
     *
     * @return string
     */
    public function getEventSource(): string
    {
        return $this->attributes->eventSource;
    }

    /**
     * Return the region.
     *
     * @return string
     */
    public function getRegion(): string
    {
        return $this->attributes->awsRegion;
    }

    /**
     * Return the event time.
     *
     * @return \Carbon\Carbon
     */
    public function getEventTime(): Carbon
    {
        return Carbon::parse($this->attributes->eventTime);
    }

    /**
     * Return the event name.
     *
     * @return string
     */
    public function getEventName(): string
    {
        return $this->attributes->eventName;
    }

    /**
     * Return the bucket.
     *
     * @return \SineMacula\Aws\Sns\Entities\Messages\S3\Bucket
     */
    public function getBucket(): Bucket
    {
        return $this->bucket ??= new Bucket($this->attributes->s3->bucket);
    }

    /**
     * Return the object.
     *
     * @return \SineMacula\Aws\Sns\Entities\Messages\S3\S3Object
     */
    public function getObject(): S3Object
    {
        return $this->object ??= new S3Object($this->attributes->s3->object);
    }
}
