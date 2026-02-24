<?php

namespace SineMacula\Aws\Sns\Events;

use SineMacula\Aws\Sns\Entities\Messages\Contracts\NotificationInterface;

/**
 * AWS SNS notification received event.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 */
class NotificationReceived
{
    /**
     * Create a new event instance.
     *
     * @param  \SineMacula\Aws\Sns\Entities\Messages\Contracts\NotificationInterface  $notification
     */
    public function __construct(

        /** @var \SineMacula\Aws\Sns\Entities\Messages\Contracts\NotificationInterface The SNS message. */
        protected NotificationInterface $notification,

    ) {}

    /**
     * Return the notification.
     *
     * @return \SineMacula\Aws\Sns\Entities\Messages\Contracts\NotificationInterface
     */
    public function getNotification(): NotificationInterface
    {
        return $this->notification;
    }

    /**
     * Return the notification for a specific notification type.
     *
     * @template TNotification of \SineMacula\Aws\Sns\Entities\Messages\Contracts\NotificationInterface
     *
     * @param  class-string<TNotification>  $expectedNotificationClass
     * @return TNotification
     *
     * @throws \InvalidArgumentException
     */
    protected function getValidatedNotification(string $expectedNotificationClass): NotificationInterface
    {
        if (!$this->notification instanceof $expectedNotificationClass) {
            throw new \InvalidArgumentException('Invalid notification type');
        }

        return $this->notification;
    }
}
