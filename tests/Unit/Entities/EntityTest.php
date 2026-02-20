<?php

declare(strict_types = 1);

namespace Tests\Unit\Entities;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Entity;
use Tests\TestCase;

/**
 * EntityTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(Entity::class)]
final class EntityTest extends TestCase
{
    /**
     * Uses a getter when accessing magic properties.
     *
     * @return void
     */
    #[Test]
    public function itUsesAGetterWhenAccessingMagicProperties(): void
    {
        $entity = new class (new \stdClass) extends Entity {
            /**
             * Returns the computed property.
             *
             * @return string
             */
            public function getComputedValue(): string
            {
                return 'computed';
            }
        };

        self::assertSame('computed', $entity->__get('computed_value'));
    }

    /**
     * Reads magic properties from attributes.
     *
     * @return void
     */
    #[Test]
    public function itReadsMagicPropertiesFromAttributes(): void
    {
        $entity = new class (['raw' => 'value']) extends Entity {};

        self::assertSame('value', $entity->__get('raw'));
    }

    /**
     * Throws when magic property does not exist.
     *
     * @return void
     */
    #[Test]
    public function itThrowsWhenMagicPropertyDoesNotExist(): void
    {
        $entity = new class (new \stdClass) extends Entity {};

        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('Property \'missing\' does not exist.');

        $entity->__get('missing');
    }

    /**
     * Constructs from stdClass attributes.
     *
     * @return void
     */
    #[Test]
    public function itConstructsFromStdClassAttributes(): void
    {
        $attributes = (object) ['value' => 'from-object'];
        $entity     = new class ($attributes) extends Entity {};

        self::assertSame(['value' => 'from-object'], $entity->toArray());
    }

    /**
     * Handles unencodable array input gracefully.
     *
     * @return void
     */
    #[Test]
    public function itHandlesUnencodableArrayInputGracefully(): void
    {
        $entity = new class (['invalid' => NAN]) extends Entity {};

        self::assertSame([], $entity->toArray());
    }

    /**
     * Converts snake case to pascal case.
     *
     * @return void
     */
    #[Test]
    public function itConvertsSnakeCaseToPascalCase(): void
    {
        $entity = new class (new \stdClass) extends Entity {};

        self::assertSame('AwsSnsMessage', $entity->convertToPascalCase('aws_sns_message'));
    }

    /**
     * Returns an empty array when encoding attributes fails.
     *
     * @return void
     */
    #[Test]
    public function itReturnsAnEmptyArrayWhenEncodingAttributesFails(): void
    {
        $entity = new class ((object) ['invalid' => NAN]) extends Entity {};

        self::assertSame([], $entity->toArray());
    }
}
