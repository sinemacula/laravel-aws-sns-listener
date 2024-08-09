<?php

namespace SineMacula\Aws\Sns;

/**
 * AWS SNS topic manager.
 *
 * Handles the registration of SNS topics.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class TopicManager
{
    /**
     * Create a new topic manager.
     *
     * @param  array  $topics
     */
    public function __construct(

        /** The SNS topics */
        protected array $topics = []

    ) {}

    /**
     * Register a new SNS topic.
     *
     * @param  string  $topic
     * @return static
     */
    public function register(string $topic): static
    {
        if (!$this->isTopicRegistered($topic)) {
            $this->topics[] = $topic;
        }

        return $this;
    }

    /**
     * Return the registered topics.
     *
     * @return array
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * Determine if the given topic is registered.
     *
     * @param  string  $topic
     * @return bool
     */
    public function isTopicRegistered(string $topic): bool
    {
        return in_array($topic, $this->topics);
    }
}
