<?php

declare(strict_types = 1);

namespace SineMacula\Aws\Sns\Entities\Messages\Contracts;

/**
 * Generic SNS notification interface.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
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
