<?php

declare(strict_types = 1);

namespace SineMacula\Aws\Sns\Events;

use SineMacula\Aws\Sns\Entities\Messages\Contracts\CloudWatchNotificationInterface;

/**
 * AWS SNS Cloud Watch notification received event.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 */
final class CloudWatchNotificationReceived extends NotificationReceived
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
    #[\Override]
    public function getNotification(): CloudWatchNotificationInterface
    {
        return $this->getValidatedNotification(CloudWatchNotificationInterface::class);
    }
}
