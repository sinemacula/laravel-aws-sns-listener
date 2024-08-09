<?php

namespace SineMacula\Aws\Sns\Events;

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
}
