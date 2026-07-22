<?php

declare(strict_types = 1);

namespace SineMacula\Aws\Sns\Entities\Messages;

use SineMacula\Aws\Sns\Entities\Messages\Contracts\SubscriptionConfirmationInterface;

/**
 * AWS SNS topic subscription confirmation message instance.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 */
final class SubscriptionConfirmation extends Message implements SubscriptionConfirmationInterface
{
    /**
     * Return the url to confirm the subscription to the topic.
     *
     * @return string
     */
    #[\Override]
    public function getSubscribeUrl(): string
    {
        $subscribeUrl = $this->message['SubscribeURL'] ?? null;

        return is_string($subscribeUrl)
            ? $subscribeUrl
            : '';
    }
}
