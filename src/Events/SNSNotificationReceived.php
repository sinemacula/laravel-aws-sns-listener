<?php

declare(strict_types = 1);

namespace SineMacula\Aws\Sns\Events;

use SineMacula\Aws\Sns\Entities\Messages\Contracts\SNSNotificationInterface;

/**
 * AWS SNS generic notification received event.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 */
final class SNSNotificationReceived extends NotificationReceived
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
    #[\Override]
    public function getNotification(): SNSNotificationInterface
    {
        return $this->getValidatedNotification(SNSNotificationInterface::class);
    }
}
