<?php

declare(strict_types = 1);

namespace SineMacula\Aws\Sns\Events;

use SineMacula\Aws\Sns\Entities\Messages\Contracts\SesNotificationInterface;

/**
 * AWS SNS SES notification received event.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 */
final class SesNotificationReceived extends NotificationReceived
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
    #[\Override]
    public function getNotification(): SesNotificationInterface
    {
        return $this->getValidatedNotification(SesNotificationInterface::class);
    }
}
