<?php

namespace SineMacula\Aws\Sns\Events;

use InvalidArgumentException;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\SNSNotificationInterface;

/**
 * AWS SNS generic notification received event.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class SNSNotificationReceived extends NotificationReceived
{
    /**
     * Create a new event instance.
     *
     * @param  \SineMacula\Aws\Sns\Entities\Messages\Contracts\SNSNotificationInterface  $notification
     */
    public function __construct(SNSNotificationInterface $notification)
    {
        parent::__construct($notification);
    }

    /**
     * Return the notification.
     *
     * @return \SineMacula\Aws\Sns\Entities\Messages\Contracts\SNSNotificationInterface
     */
    public function getNotification(): SNSNotificationInterface
    {
        if (!$this->notification instanceof SNSNotificationInterface) {
            throw new InvalidArgumentException('Invalid notification type');
        }

        return $this->notification;
    }
}
