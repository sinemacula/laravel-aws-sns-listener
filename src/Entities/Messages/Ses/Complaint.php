<?php

namespace SineMacula\Aws\Sns\Entities\Messages\Ses;

use Carbon\Carbon;
use SineMacula\Aws\Sns\Entities\Entity;

/**
 * AWS SES complaint instance.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class Complaint extends Entity
{
    /**
     * Return the user agent.
     *
     * @return string|null
     */
    public function getUserAgent(): ?string
    {
        return $this->attributes->userAgent ?? null;
    }

    /**
     * Return the complained recipients.
     *
     * @return array
     */
    public function getComplainedRecipients(): array
    {
        return (array) $this->attributes->complainedRecipients;
    }

    /**
     * Return the feedback type.
     *
     * @return string|null
     */
    public function getFeedbackType(): ?string
    {
        return $this->attributes->complaintFeedbackType ?? null;
    }

    /**
     * Return the arrival date.
     *
     * @return \Carbon\Carbon|null
     */
    public function getArrivalDate(): ?Carbon
    {
        return $this->attributes->arrivalDate
            ? Carbon::parse($this->attributes->arrivalDate)
            : null;
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
}
