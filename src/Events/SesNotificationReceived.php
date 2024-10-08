<?php

namespace SineMacula\Aws\Sns\Events;

use InvalidArgumentException;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\SesNotificationInterface;

/**
 * AWS SNS SES notification received event.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class SesNotificationReceived extends NotificationReceived
{
    /**
     * Create a new event instance.
     *
     * @param  \SineMacula\Aws\Sns\Entities\Messages\Contracts\SesNotificationInterface  $notification
     */
    public function __construct(SesNotificationInterface $notification)
    {
        parent::__construct($notification);
    }

    /**
     * Return the notification.
     *
     * @return \SineMacula\Aws\Sns\Entities\Messages\Contracts\SesNotificationInterface
     */
    public function getNotification(): SesNotificationInterface
    {
        if (!$this->notification instanceof SesNotificationInterface) {
            throw new InvalidArgumentException('Invalid notification type');
        }

        return $this->notification;
    }
}
