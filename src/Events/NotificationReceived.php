<?php

namespace SineMacula\Aws\Sns\Events;

use SineMacula\Aws\Sns\Entities\Messages\Contracts\NotificationInterface;

/**
 * AWS SNS notification received event.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class NotificationReceived
{
    /**
     * Create a new event instance.
     *
     * @param  \SineMacula\Aws\Sns\Entities\Messages\Contracts\NotificationInterface  $notification
     */
    public function __construct(

        /** The SNS message */
        protected NotificationInterface $notification

    ) {}
}
