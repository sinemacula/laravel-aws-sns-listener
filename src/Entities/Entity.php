<?php

namespace SineMacula\Aws\Sns\Entities;

use Exception;
use stdClass;

/**
 * Base entity.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
abstract class Entity
{
    /** @var \stdClass */
    protected stdClass $attributes;

    /**
     * Create a new Entity instance.
     *
     * @param  \stdClass|array|null  $attributes
     */
    public function __construct(stdClass|array|null $attributes = null)
    {
        $this->attributes = json_decode(
            json_encode($attributes ?? new stdClass), false
        );
    }

    /**
     * Magic getter to dynamically access attributes.
     *
     * @param  string  $property
     * @return mixed
     *
     * @throws \Exception
     */
    public function __get(string $property): mixed
    {
        $getter = "get{$this->convertToPascalCase($property)}";

        if (method_exists($this, $getter)) {
            return $this->{$getter}();
        }

        if (isset($this->attributes->{$property})) {
            return $this->attributes->{$property};
        }

        throw new Exception("Property '{$property}' does not exist.");
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
     * @return array
     */
    public function toArray(): array
    {
        return json_decode(json_encode($this->attributes), true);
    }
}
