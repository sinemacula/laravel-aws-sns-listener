<?php

declare(strict_types = 1);

namespace Tests\Unit\Facades;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Facades\SnsTopicManager;
use SineMacula\Aws\Sns\TopicManager;
use Tests\TestCase;

/**
 * SnsTopicManagerTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(SnsTopicManager::class)]
final class SnsTopicManagerTest extends TestCase
{
    /**
     * Proxies calls to the topic manager binding.
     *
     * @return void
     */
    #[Test]
    public function itProxiesCallsToTheTopicManagerBinding(): void
    {
        $this->appInstance()->singleton('sns-topic-manager', static fn (): TopicManager => new TopicManager(['topic-a']));

        self::assertTrue(SnsTopicManager::isTopicRegistered('topic-a'));
        self::assertFalse(SnsTopicManager::isTopicRegistered('topic-b'));

        SnsTopicManager::register('topic-b');

        self::assertSame(['topic-a', 'topic-b'], SnsTopicManager::getTopics());
    }
}
