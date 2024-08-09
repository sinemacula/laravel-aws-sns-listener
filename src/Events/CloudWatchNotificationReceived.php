<?php

namespace SineMacula\Aws\Sns\Events;

use InvalidArgumentException;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\CloudWatchNotificationInterface;

/**
 * AWS SNS Cloud Watch notification received event.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class CloudWatchNotificationReceived extends NotificationReceived
{
    /**
     * Create a new event instance.
     *
     * @param  \SineMacula\Aws\Sns\Entities\Messages\Contracts\CloudWatchNotificationInterface  $notification
     */
    public function __construct(CloudWatchNotificationInterface $notification)
    {
        parent::__construct($notification);
    }

    /**
     * Return the notification.
     *
     * @return \SineMacula\Aws\Sns\Entities\Messages\Contracts\CloudWatchNotificationInterface
     */
    public function getNotification(): CloudWatchNotificationInterface
    {
        if (!$this->notification instanceof CloudWatchNotificationInterface) {
            throw new InvalidArgumentException('Invalid notification type');
        }

        return $this->notification;
    }
}
