<?php

namespace SineMacula\Aws\Sns\Entities\Messages\Contracts;

/**
 * Subscription confirmation message interface.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
interface SubscriptionConfirmationInterface extends MessageInterface
{
    /**
     * Return the url to confirm the subscription to the topic.
     *
     * @return string
     */
    public function getSubscribeUrl(): string;
}
