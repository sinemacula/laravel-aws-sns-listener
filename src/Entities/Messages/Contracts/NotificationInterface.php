<?php

namespace SineMacula\Aws\Sns\Entities\Messages\Contracts;

/**
 * Notification interface.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
interface NotificationInterface extends MessageInterface
{
    /**
     * Return the url to unsubscribe from the topic.
     *
     * @return string|null
     */
    public function getUnsubscribeUrl(): ?string;
}
