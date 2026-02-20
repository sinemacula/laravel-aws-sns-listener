<?php

declare(strict_types = 1);

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\TopicManager;
use Tests\TestCase;

/**
 * TopicManagerTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(TopicManager::class)]
final class TopicManagerTest extends TestCase
{
    /**
     * It registers topics without duplicates.
     *
     * @return void
     */
    #[Test]
    public function itRegistersTopicsWithoutDuplicates(): void
    {
        $manager = new TopicManager(['topic-a']);

        $result = $manager
            ->register('topic-a')
            ->register('topic-b');

        self::assertSame($manager, $result);
        self::assertSame(['topic-a', 'topic-b'], $manager->getTopics());
    }

    /**
     * It checks if topics are registered.
     *
     * @return void
     */
    #[Test]
    public function itChecksIfTopicsAreRegistered(): void
    {
        $manager = new TopicManager(['topic-a']);

        self::assertTrue($manager->isTopicRegistered('topic-a'));
        self::assertFalse($manager->isTopicRegistered('topic-b'));
    }
}
