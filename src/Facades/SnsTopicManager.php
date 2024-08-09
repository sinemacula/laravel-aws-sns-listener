<?php

namespace SineMacula\Aws\Sns\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * AWS SNS topic manager facade.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 *
 * @method static static register(string $topic)
 * @method static array getTopics()
 * @method static bool isTopicRegistered(string $topic)
 */
class SnsTopicManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'sns-topic-manager';
    }
}
