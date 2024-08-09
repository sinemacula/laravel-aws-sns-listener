<?php

namespace SineMacula\Aws\Sns\Entities\Messages\Ses;

use Carbon\Carbon;
use SineMacula\Aws\Sns\Entities\Entity;

/**
 * AWS SES mail instance.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class Mail extends Entity
{
    /**
     * Return the message identifier.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->attributes->messageId;
    }

    /**
     * Return the timestamp.
     *
     * @return \Carbon\Carbon
     */
    public function getTimestamp(): Carbon
    {
        return Carbon::parse($this->attributes->timestamp);
    }

    /**
     * Return the source.
     *
     * @return string
     */
    public function getSource(): string
    {
        return $this->attributes->source;
    }

    /**
     * Return the source ARN.
     *
     * @return string
     */
    public function getSourceArn(): string
    {
        return $this->attributes->sourceArn;
    }

    /**
     * Return the source IP address.
     *
     * @return string
     */
    public function getSourceIp(): string
    {
        return $this->attributes->sourceIp;
    }

    /**
     * Return the sending account identifier.
     *
     * @return string
     */
    public function getSendingAccountId(): string
    {
        return $this->attributes->sendingAccountId;
    }

    /**
     * Return the caller identity.
     *
     * @return string
     */
    public function getCallerIdentity(): string
    {
        return $this->attributes->callerIdentity;
    }

    /**
     * Return the destinations.
     *
     * @return array
     */
    public function getDestinations(): array
    {
        return (array) $this->attributes->destination;
    }

    /**
     * Determine whether the headers are truncated.
     *
     * @return bool
     */
    public function areHeadersTruncated(): bool
    {
        return (bool) ($this->attributes->headersTruncated ?? false);
    }

    /**
     * Return the headers.
     *
     * @return array|null
     */
    public function getHeaders(): ?array
    {
        return isset($this->attributes->headers) ? (array) $this->attributes->headers : null;
    }

    /**
     * Return the common headers.
     *
     * @return array|null
     */
    public function getCommonHeaders(): ?array
    {
        return isset($this->attributes->commonHeaders) ? (array) $this->attributes->commonHeaders : null;
    }
}
