<?php

declare(strict_types = 1);

namespace SineMacula\Aws\Sns\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * AWS SNS topic manager facade.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @method static static register(string $topic)
 * @method static array<int, string> getTopics()
 * @method static bool isTopicRegistered(string $topic)
 */
final class SnsTopicManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    #[\Override]
    protected static function getFacadeAccessor(): string
    {
        return 'sns-topic-manager';
    }
}
