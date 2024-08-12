<?php

namespace SineMacula\Aws\Sns;

use Aws\Sns\Message;
use SineMacula\Aws\Sns\Entities\Messages\CloudWatch\Notification as CloudWatchNotification;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\MessageInterface;
use SineMacula\Aws\Sns\Entities\Messages\S3\Notification as S3Notification;
use SineMacula\Aws\Sns\Entities\Messages\Ses\Notification as SesNotification;
use SineMacula\Aws\Sns\Entities\Messages\SubscriptionConfirmation;
use SineMacula\Aws\Sns\Entities\Messages\TestNotification;
use SineMacula\Aws\Sns\Exceptions\UnsupportedMessageException;

/**
 * AWS SNS message factory.
 *
 * Creates native instances of the various SNS messages.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class MessageFactory
{
    /**
     * Create a native SNS message instance.
     *
     * @param  \Aws\Sns\Message  $message
     * @return \SineMacula\Aws\Sns\Entities\Messages\Contracts\MessageInterface
     */
    public static function make(Message $message): MessageInterface
    {
        return match (true) {
            self::isSubscriptionConfirmation($message) => new SubscriptionConfirmation($message),
            self::isS3Notification($message)           => new S3Notification($message),
            self::isSesNotification($message)          => new SesNotification($message),
            self::isCloudWatchNotification($message)   => new CloudWatchNotification($message),
            self::isTestNotification($message)         => new TestNotification($message),
            default                                    => throw new UnsupportedMessageException('Unsupported SNS message type: ' . $message['Type'] ?? 'Undefined')
        };
    }

    /**
     * Determine if the given message is a subscription confirmation.
     *
     * @param  \Aws\Sns\Message  $message
     * @return bool
     */
    private static function isSubscriptionConfirmation(Message $message): bool
    {
        return $message['Type'] === 'SubscriptionConfirmation';
    }

    /**
     * Determine if the given message is an S3 notification.
     *
     * @param  \Aws\Sns\Message  $message
     * @return bool
     */
    private static function isS3Notification(Message $message): bool
    {
        $message = json_decode($message['Message'], true);

        return isset($message['Records'][0]['s3']);
    }

    /**
     * Determine if the given message payload is an SES notification.
     *
     * @param  \Aws\Sns\Message  $message
     * @return bool
     */
    private static function isSesNotification(Message $message): bool
    {
        $message = json_decode($message['Message'], true);

        return isset($message['notificationType'])
            && in_array($message['notificationType'], [
                'Bounce',
                'Complaint',
                'Delivery'
            ]);
    }

    /**
     * Determine if the given message payload is a Cloud Watch notification.
     *
     * @param  \Aws\Sns\Message  $message
     * @return bool
     */
    private static function isCloudWatchNotification(Message $message): bool
    {
        $message = json_decode($message['Message'], true);

        return isset($message['AlarmName'])
            && isset($message['NewStateValue']);
    }

    /**
     * Determine if the given message payload is a test notification.
     *
     * @param  \Aws\Sns\Message  $message
     * @return bool
     */
    private static function isTestNotification(Message $message): bool
    {
        $message = json_decode($message['Message'], true);

        return str_ends_with($message['Event'] ?? '', ':TestEvent');
    }
}
