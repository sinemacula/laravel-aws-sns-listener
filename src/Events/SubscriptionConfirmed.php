<?php

declare(strict_types = 1);

namespace SineMacula\Aws\Sns\Events;

use SineMacula\Aws\Sns\Entities\Messages\Contracts\SubscriptionConfirmationInterface;

/**
 * AWS SNS subscription confirmed event.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 */
final class SubscriptionConfirmed
{
    /**
     * Create a new event instance.
     *
     * @param  \SineMacula\Aws\Sns\Entities\Messages\Contracts\SubscriptionConfirmationInterface  $message
     */
    public function __construct(

        /** @var \SineMacula\Aws\Sns\Entities\Messages\Contracts\SubscriptionConfirmationInterface The SNS message instance. */
        private SubscriptionConfirmationInterface $message,
    ) {}

    /**
     * Return the message.
     *
     * @return \SineMacula\Aws\Sns\Entities\Messages\Contracts\SubscriptionConfirmationInterface
     */
    public function getMessage(): SubscriptionConfirmationInterface
    {
        return $this->message;
    }
}
