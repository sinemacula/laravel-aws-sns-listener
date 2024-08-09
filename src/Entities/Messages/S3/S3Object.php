<?php

namespace SineMacula\Aws\Sns\Entities\Messages\S3;

use SineMacula\Aws\Sns\Entities\Entity;

/**
 * AWS S3 object instance.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class S3Object extends Entity
{
    /**
     * Return the key.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->attributes->key;
    }

    /**
     * Return the size.
     *
     * @return int
     */
    public function getSize(): int
    {
        return $this->attributes->size;
    }

    /**
     * Return the eTag.
     *
     * @return string
     */
    public function getETag(): string
    {
        return $this->attributes->eTag;
    }

    /**
     * Return the version identifier.
     *
     * @return string
     */
    public function getVersionId(): string
    {
        return $this->attributes->versionId;
    }

    /**
     * Return the sequencer.
     *
     * @return string
     */
    public function getSequencer(): string
    {
        return $this->attributes->sequencer;
    }
}
