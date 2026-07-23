<?php

declare(strict_types = 1);

namespace SineMacula\Aws\Sns\Events;

use SineMacula\Aws\Sns\Entities\Messages\Contracts\NotificationInterface;

/**
 * AWS SNS notification received event.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @inheritable
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
     * @param  class-string<TNotification>  $expectedNotification
     * @return TNotification
     *
     * @throws \InvalidArgumentException
     */
    protected function getValidatedNotification(string $expectedNotification): NotificationInterface
    {
        if (!$this->notification instanceof $expectedNotification) {
            throw new \InvalidArgumentException('Invalid notification type');
        }

        return $this->notification;
    }
}
