<?php

namespace SineMacula\Aws\Sns\Events;

use SineMacula\Aws\Sns\Entities\Messages\Contracts\S3NotificationInterface;

/**
 * AWS SNS S3 notification received event.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class S3NotificationReceived extends NotificationReceived
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
}
