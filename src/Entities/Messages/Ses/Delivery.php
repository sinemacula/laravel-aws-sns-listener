<?php

namespace SineMacula\Aws\Sns\Entities\Messages\Ses;

use Carbon\Carbon;
use SineMacula\Aws\Sns\Entities\Entity;

/**
 * AWS SES delivery instance.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class Delivery extends Entity
{
    /**
     * Return the name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->attributes->name;
    }

    /**
     * Return the recipients.
     *
     * @return array
     */
    public function getRecipients(): array
    {
        return (array) $this->attributes->recipients;
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
     * Return the processing time.
     *
     * @return int
     */
    public function getProcessingTime(): int
    {
        return (int) $this->attributes->processingTimeMillis;
    }

    /**
     * Return the reporting MTA.
     *
     * @return string
     */
    public function getReportingMta(): string
    {
        return $this->attributes->reportingMTA;
    }

    /**
     * Return the SMTP response.
     *
     * @return string
     */
    public function getSmtpResponse(): string
    {
        return $this->attributes->smtpResponse;
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
