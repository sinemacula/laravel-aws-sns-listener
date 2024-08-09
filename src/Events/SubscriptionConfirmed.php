<?php

namespace SineMacula\Aws\Sns\Events;

use SineMacula\Aws\Sns\Entities\Messages\Contracts\SubscriptionConfirmationInterface;

/**
 * AWS SNS subscription confirmed event.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class SubscriptionConfirmed
{
    /**
     * Create a new event instance.
     *
     * @param  \SineMacula\Aws\Sns\Entities\Messages\Contracts\SubscriptionConfirmationInterface  $message
     */
    public function __construct(

        /** The SNS message instance */
        protected SubscriptionConfirmationInterface $message

    ) {}
}
