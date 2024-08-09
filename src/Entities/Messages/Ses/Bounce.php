<?php

namespace SineMacula\Aws\Sns\Entities\Messages\Ses;

use Carbon\Carbon;
use SineMacula\Aws\Sns\Entities\Entity;

/**
 * AWS SES bounce instance.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class Bounce extends Entity
{
    /**
     * Return the type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->attributes->bounceType;
    }

    /**
     * Return the subtype.
     *
     * @return string
     */
    public function getSubtype(): string
    {
        return $this->attributes->bounceSubType;
    }

    /**
     * Return the bounced recipients.
     *
     * @return array
     */
    public function getBouncedRecipients(): array
    {
        return (array) $this->attributes->bouncedRecipients;
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
     * Return the feedback identifier.
     *
     * @return string
     */
    public function getFeedbackId(): string
    {
        return $this->attributes->feedbackId;
    }

    /**
     * Return the reporting MTA.
     *
     * @return string|null
     */
    public function getReportingMta(): ?string
    {
        return $this->attributes->reportingMTA ?? null;
    }

    /**
     * Return the remote MTA IP address.
     *
     * @return string
     */
    public function getRemoteMtaIp(): string
    {
        return $this->attributes->remoteMtaIp;
    }
}
