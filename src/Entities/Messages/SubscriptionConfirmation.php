<?php

namespace SineMacula\Aws\Sns\Entities\Messages;

use SineMacula\Aws\Sns\Entities\Messages\Contracts\SubscriptionConfirmationInterface;

/**
 * AWS SNS topic subscription confirmation message instance.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class SubscriptionConfirmation extends Message implements SubscriptionConfirmationInterface
{
    /**
     * Return the url to confirm the subscription to the topic.
     *
     * @return string
     */
    public function getSubscribeUrl(): string
    {
        return $this->message['SubscribeURL'];
    }
}
