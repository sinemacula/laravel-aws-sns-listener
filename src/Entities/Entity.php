<?php

namespace SineMacula\Aws\Sns\Entities;

/**
 * Base entity.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 */
abstract class Entity
{
    /** @var \stdClass Attributes value. */
    protected \stdClass $attributes;

    /**
     * Create a new Entity instance.
     *
     * @param  array<string, mixed>|\stdClass|null  $attributes
     */
    public function __construct(array|\stdClass|null $attributes = null)
    {
        if ($attributes instanceof \stdClass) {
            $this->attributes = $attributes;
            return;
        }

        $encoded = json_encode($attributes ?? []);

        $this->attributes = is_string($encoded)
            ? (json_decode($encoded) ?? new \stdClass)
            : new \stdClass;
    }

    /**
     * Magic getter to dynamically access attributes.
     *
     * @param  string  $property
     * @return mixed
     *
     * @throws \OutOfBoundsException
     */
    public function __get(string $property): mixed
    {
        $getter = "get{$this->convertToPascalCase($property)}";

        if (method_exists($this, $getter)) {
            return call_user_func([$this, $getter]);
        }

        if (isset($this->attributes->{$property})) {
            return $this->attributes->{$property};
        }

        throw new \OutOfBoundsException("Property '{$property}' does not exist.");
    }

    /**
     * Convert the given string to pascal case.
     *
     * @param  string  $string
     * @return string
     */
    public function convertToPascalCase(string $string): string
    {
        return str_replace('_', '', ucwords($string, '_'));
    }

    /**
     * Get the entity as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $encoded_attributes = json_encode($this->attributes);
        $decoded_attributes = is_string($encoded_attributes)
            ? json_decode($encoded_attributes, true)
            : null;

        return is_array($decoded_attributes)
            ? $decoded_attributes
            : [];
    }
}
