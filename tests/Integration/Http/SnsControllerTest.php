<?php

declare(strict_types = 1);

namespace Tests\Integration\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\CloudWatchNotificationInterface;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\MessageInterface;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\NotificationInterface;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\S3NotificationInterface;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\SesNotificationInterface;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\SNSNotificationInterface;
use SineMacula\Aws\Sns\Entities\Messages\Contracts\SubscriptionConfirmationInterface;
use SineMacula\Aws\Sns\Events\CloudWatchNotificationReceived;
use SineMacula\Aws\Sns\Events\NotificationReceived;
use SineMacula\Aws\Sns\Events\S3NotificationReceived;
use SineMacula\Aws\Sns\Events\SesNotificationReceived;
use SineMacula\Aws\Sns\Events\SNSNotificationReceived;
use SineMacula\Aws\Sns\Events\SubscriptionConfirmed;
use SineMacula\Aws\Sns\Http\Controllers\SnsController;
use SineMacula\Aws\Sns\TopicManager;
use Tests\TestCase;

/**
 * SnsControllerTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(SnsController::class)]
final class SnsControllerTest extends TestCase
{
    /**
     * Reset the topic manager binding.
     *
     * @return void
     */
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->appInstance()->singleton('sns-topic-manager', static fn (): TopicManager => new TopicManager);
    }

    /**
     * It throws when the sns message is missing.
     *
     * @return void
     */
    #[Test]
    public function itThrowsWhenTheSnsMessageIsMissing(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('SNS message is missing or invalid.');

        (new SnsController)->hook(Request::create('/hooks/sns', 'POST'));
    }

    /**
     * It returns bad request for unsupported message types.
     *
     * @return void
     */
    #[Test]
    public function itReturnsBadRequestForUnsupportedMessageTypes(): void
    {
        $message = self::createStub(MessageInterface::class);

        $response = $this->dispatchWithMessage($message);

        self::assertSame(400, $response->getStatusCode());
        self::assertSame(['message' => 'Unsupported SNS notification type'], $response->getData(true));
    }

    /**
     * It rejects subscription confirmation for unregistered topics.
     *
     * @return void
     */
    #[Test]
    public function itRejectsSubscriptionConfirmationForUnregisteredTopics(): void
    {
        Event::fake();

        $message = $this->createMock(SubscriptionConfirmationInterface::class);
        $message->method('getTopic')->willReturn('arn:aws:sns:us-east-1:123456789012:unknown');

        $response = $this->dispatchWithMessage($message);

        self::assertSame(400, $response->getStatusCode());
        self::assertSame(['message' => 'SNS topic not registered'], $response->getData(true));
        Event::assertNotDispatched(SubscriptionConfirmed::class);
    }

    /**
     * It confirms subscription for registered topics.
     *
     * @return void
     */
    #[Test]
    public function itConfirmsSubscriptionForRegisteredTopics(): void
    {
        Event::fake();
        Http::fake();

        $topicManager = $this->appInstance()->make('sns-topic-manager');
        self::assertInstanceOf(TopicManager::class, $topicManager);
        $topicManager->register('arn:aws:sns:us-east-1:123456789012:known');

        $message = $this->createMock(SubscriptionConfirmationInterface::class);
        $message->method('getTopic')->willReturn('arn:aws:sns:us-east-1:123456789012:known');
        $message->method('getSubscribeUrl')->willReturn('https://example.test/confirm');

        $response = $this->dispatchWithMessage($message);

        self::assertSame(200, $response->getStatusCode());
        self::assertSame(['message' => 'SNS topic subscription confirmed'], $response->getData(true));

        Event::assertDispatched(SubscriptionConfirmed::class);
        Http::assertSentCount(1);
    }

    /**
     * It dispatches base and generic events for generic notifications.
     *
     * @return void
     */
    #[Test]
    public function itDispatchesBaseAndGenericEventsForGenericNotifications(): void
    {
        Event::fake();

        $notification = self::createStub(SNSNotificationInterface::class);

        $response = $this->dispatchWithMessage($notification);

        self::assertSame(200, $response->getStatusCode());
        self::assertSame(['message' => 'SNS notification received'], $response->getData(true));

        Event::assertDispatched(NotificationReceived::class);
        Event::assertDispatched(SNSNotificationReceived::class);
        Event::assertNotDispatched(CloudWatchNotificationReceived::class);
        Event::assertNotDispatched(S3NotificationReceived::class);
        Event::assertNotDispatched(SesNotificationReceived::class);
    }

    /**
     * It dispatches cloudwatch event for cloudwatch notifications.
     *
     * @return void
     */
    #[Test]
    public function itDispatchesCloudwatchEventForCloudwatchNotifications(): void
    {
        Event::fake();

        $notification = self::createStub(CloudWatchNotificationInterface::class);

        $this->dispatchWithMessage($notification);

        Event::assertDispatched(NotificationReceived::class);
        Event::assertDispatched(CloudWatchNotificationReceived::class);
    }

    /**
     * It dispatches s3 event for s3 notifications.
     *
     * @return void
     */
    #[Test]
    public function itDispatchesS3EventForS3Notifications(): void
    {
        Event::fake();

        $notification = self::createStub(S3NotificationInterface::class);

        $this->dispatchWithMessage($notification);

        Event::assertDispatched(NotificationReceived::class);
        Event::assertDispatched(S3NotificationReceived::class);
    }

    /**
     * It dispatches ses event for ses notifications.
     *
     * @return void
     */
    #[Test]
    public function itDispatchesSesEventForSesNotifications(): void
    {
        Event::fake();

        $notification = self::createStub(SesNotificationInterface::class);

        $this->dispatchWithMessage($notification);

        Event::assertDispatched(NotificationReceived::class);
        Event::assertDispatched(SesNotificationReceived::class);
    }

    /**
     * It dispatches only base event for untyped notifications.
     *
     * @return void
     */
    #[Test]
    public function itDispatchesOnlyBaseEventForUntypedNotifications(): void
    {
        Event::fake();

        $notification = self::createStub(NotificationInterface::class);

        $this->dispatchWithMessage($notification);

        Event::assertDispatched(NotificationReceived::class);
        Event::assertNotDispatched(CloudWatchNotificationReceived::class);
        Event::assertNotDispatched(S3NotificationReceived::class);
        Event::assertNotDispatched(SesNotificationReceived::class);
        Event::assertNotDispatched(SNSNotificationReceived::class);
    }

    /**
     * Dispatch a request with a prepared SNS message attribute.
     *
     * @param  \SineMacula\Aws\Sns\Entities\Messages\Contracts\MessageInterface  $message
     * @return \Illuminate\Http\JsonResponse
     */
    private function dispatchWithMessage(MessageInterface $message): JsonResponse
    {
        $request = Request::create('/hooks/sns', 'POST');
        $request->attributes->set('sns_message', $message);

        return (new SnsController)->hook($request);
    }
}
