<?php

namespace SineMacula\Aws\Sns\Entities\Messages;

use SineMacula\Aws\Sns\Entities\Messages\Contracts\SNSNotificationInterface;

/**
 * AWS SNS generic notification instance.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class SNSNotification extends Notification implements SNSNotificationInterface
{
    /**
     * Return the raw SNS message payload.
     *
     * @return string
     */
    public function getRawMessage(): string
    {
        return $this->getBaseMessage()['Message'];
    }
}
