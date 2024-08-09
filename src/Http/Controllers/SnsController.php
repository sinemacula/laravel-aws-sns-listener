<?php

namespace SineMacula\Aws\Sns\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\CloudWatchNotificationInterface;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\MessageInterface;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\NotificationInterface;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\S3NotificationInterface;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\SesNotificationInterface;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\SubscriptionConfirmationInterface;
use SineMacula\Aws\Sns\Events\CloudWatchNotificationReceived;
use SineMacula\Aws\Sns\Events\NotificationReceived;
use SineMacula\Aws\Sns\Events\S3NotificationReceived;
use SineMacula\Aws\Sns\Events\SesNotificationReceived;
use SineMacula\Aws\Sns\Events\SubscriptionConfirmed;
use SineMacula\Aws\Sns\Facades\SnsTopicManager;

/**
 * The AWS SNS controller.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class SnsController
{
    /**
     * Handle all SNS webhooks.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function hook(Request $request): JsonResponse
    {
        $message = $this->resolveSnsMessage($request);

        return match (true) {
            $message instanceof SubscriptionConfirmationInterface => $this->handleSubscription($message),
            $message instanceof NotificationInterface             => $this->handleNotification($message),
            default                                               => Response::json(['message' => 'Unsupported SNS notification type'], 400)
        };
    }

    /**
     * Resolve the SNS message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \SineMacula\Aws\Sns\Entities\Messages\Contracts\MessageInterface
     */
    private function resolveSnsMessage(Request $request): MessageInterface
    {
        return $request->attributes->get('sns_message');
    }

    /**
     * Handle an SNS topic subscription.
     *
     * @param  \SineMacula\Aws\Sns\Entities\Messages\Contracts\SubscriptionConfirmationInterface  $message
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleSubscription(SubscriptionConfirmationInterface $message): JsonResponse
    {
        if (!SnsTopicManager::isTopicRegistered($message->getTopic())) {
            return Response::json(['message' => 'SNS topic not registered'], 400);
        }

        Http::get($message->getSubscribeUrl());

        Event::dispatch(new SubscriptionConfirmed($message));

        return Response::json(['message' => 'SNS topic subscription confirmed']);
    }

    /**
     * Handle an SNS notification.
     *
     * @param  \SineMacula\Aws\Sns\Entities\Messages\Contracts\NotificationInterface  $notification
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleNotification(NotificationInterface $notification): JsonResponse
    {
        Event::dispatch(new NotificationReceived($notification));

        $this->handleNotificationTypes($notification);

        return Response::json(['message' => 'SNS notification received']);
    }

    /**
     * Handle specific SNS notification types.
     *
     * @param  \SineMacula\Aws\Sns\Entities\Messages\Contracts\NotificationInterface  $notification
     * @return void
     */
    private function handleNotificationTypes(NotificationInterface $notification): void
    {
        switch (true) {
            case $notification instanceof CloudWatchNotificationInterface:
                Event::dispatch(new CloudWatchNotificationReceived($notification));
                break;
            case $notification instanceof S3NotificationInterface:
                Event::dispatch(new S3NotificationReceived($notification));
                break;
            case $notification instanceof SesNotificationInterface:
                Event::dispatch(new SesNotificationReceived($notification));
                break;
        }
    }
}
