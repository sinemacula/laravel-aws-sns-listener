<?php

namespace SineMacula\Aws\Sns\Entities\Messages\Contracts;

use Carbon\Carbon;

/**
 * Cloud Watch notification interface.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
interface CloudWatchNotificationInterface extends NotificationInterface
{
    /**
     * Return the alarm name.
     *
     * @return string
     */
    public function getAlarmName(): string;

    /**
     * Return the alarm description.
     *
     * @return string|null
     */
    public function getAlarmDescription(): ?string;

    /**
     * Return the AWS account identifier.
     *
     * @return string
     */
    public function getAwsAccountId(): string;

    /**
     * Return the new state value.
     *
     * @return string
     */
    public function getNewStateValue(): string;

    /**
     * Return the new state reason.
     *
     * @return string
     */
    public function getNewStateReason(): string;

    /**
     * Return the old state value.
     *
     * @return string
     */
    public function getOldStateValue(): string;

    /**
     * Return the state change time.
     *
     * @return \Carbon\Carbon
     */
    public function getStateChangeTime(): Carbon;

    /**
     * Return the region.
     *
     * @return string
     */
    public function getRegion(): string;
}
