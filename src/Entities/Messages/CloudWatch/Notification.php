<?php

namespace SineMacula\Aws\Sns\Entities\Messages\CloudWatch;

use Carbon\Carbon;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\CloudWatchNotificationInterface;
use SineMacula\Aws\Sns\Entities\Messages\Notification as BaseNotification;

/**
 * AWS SNS Cloud Watch notification instance.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class Notification extends BaseNotification implements CloudWatchNotificationInterface
{
    /**
     * Return the alarm name.
     *
     * @return string
     */
    public function getAlarmName(): string
    {
        return $this->attributes->Message->AlarmName;
    }

    /**
     * Return the alarm description.
     *
     * @return string|null
     */
    public function getAlarmDescription(): ?string
    {
        return $this->attributes->Message->AlarmDescription ?? null;
    }

    /**
     * Return the AWS account identifier.
     *
     * @return string
     */
    public function getAwsAccountId(): string
    {
        return (string) $this->attributes->Message->AWSAccountId;
    }

    /**
     * Return the new state value.
     *
     * @return string
     */
    public function getNewStateValue(): string
    {
        return $this->attributes->Message->NewStateValue;
    }

    /**
     * Return the new state reason.
     *
     * @return string
     */
    public function getNewStateReason(): string
    {
        return $this->attributes->Message->NewStateReason;
    }

    /**
     * Return the old state value.
     *
     * @return string
     */
    public function getOldStateValue(): string
    {
        return $this->attributes->Message->OldStateValue;
    }

    /**
     * Return the state change time.
     *
     * @return \Carbon\Carbon
     */
    public function getStateChangeTime(): Carbon
    {
        return Carbon::parse($this->attributes->StateChangeTime);
    }

    /**
     * Return the region.
     *
     * @return string
     */
    public function getRegion(): string
    {
        return $this->attributes->Region;
    }
}
