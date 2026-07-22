<?php

declare(strict_types = 1);

namespace SineMacula\Aws\Sns\Events;

use SineMacula\Aws\Sns\Entities\Messages\Contracts\S3NotificationInterface;

/**
 * AWS SNS S3 notification received event.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 */
final class S3NotificationReceived extends NotificationReceived
{
    /**
     * Create a new event instance.
     *
     * @param  \SineMacula\Aws\Sns\Entities\Messages\Contracts\S3NotificationInterface  $notification
     */
    public function __construct(S3NotificationInterface $notification)
    {
        parent::__construct($notification);
    }

    /**
     * Return the notification.
     *
     * @return \SineMacula\Aws\Sns\Entities\Messages\Contracts\S3NotificationInterface
     */
    #[\Override]
    public function getNotification(): S3NotificationInterface
    {
        return $this->getValidatedNotification(S3NotificationInterface::class);
    }
}
