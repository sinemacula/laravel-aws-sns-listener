<?php

namespace SineMacula\Aws\Sns\Entities\Messages\Contracts;

/**
 * Generic SNS notification interface.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
interface SNSNotificationInterface extends NotificationInterface
{
    /**
     * Return the raw SNS message payload.
     *
     * @return string
     */
    public function getRawMessage(): string;
}
