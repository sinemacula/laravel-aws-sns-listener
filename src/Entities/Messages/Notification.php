<?php

namespace SineMacula\Aws\Sns\Entities\Messages;

/**
 * AWS SNS notification instance.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
abstract class Notification extends Message
{
    /**
     * Return the url to unsubscribe from the topic.
     *
     * @return string|null
     */
    public function getUnsubscribeUrl(): ?string
    {
        return $this->message['UnsubscribeURL'];
    }
}
