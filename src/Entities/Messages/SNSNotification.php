<?php

declare(strict_types = 1);

namespace SineMacula\Aws\Sns\Entities\Messages;

use SineMacula\Aws\Sns\Entities\Messages\Contracts\SNSNotificationInterface;

/**
 * AWS SNS generic notification instance.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 */
final class SNSNotification extends Notification implements SNSNotificationInterface
{
    /**
     * Return the raw SNS message payload.
     *
     * @return string
     */
    #[\Override]
    public function getRawMessage(): string
    {
        $rawMessage = $this->getBaseMessage()['Message'] ?? null;

        return is_string($rawMessage)
            ? $rawMessage
            : '';
    }
}
