<?php

namespace SineMacula\Aws\Sns\Entities\Messages\S3;

use SineMacula\Aws\Sns\Entities\Entity;

/**
 * AWS S3 object instance.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
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
     * @return int|null
     */
    public function getSize(): ?int
    {
        return isset($this->attributes->size) ? (int) $this->attributes->size : null;
    }

    /**
     * Return the eTag.
     *
     * @return string|null
     */
    public function getETag(): ?string
    {
        return isset($this->attributes->eTag) ? (string) $this->attributes->eTag : null;
    }

    /**
     * Return the version identifier.
     *
     * @return string|null
     */
    public function getVersionId(): ?string
    {
        return isset($this->attributes->versionId) ? (string) $this->attributes->versionId : null;
    }

    /**
     * Return the sequencer.
     *
     * @return string|null
     */
    public function getSequencer(): ?string
    {
        return isset($this->attributes->sequencer) ? (string) $this->attributes->sequencer : null;
    }
}
