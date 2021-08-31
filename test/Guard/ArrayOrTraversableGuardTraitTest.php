<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\Guard;

use laminas\stdlib\arrayobject;
use Laminas\Stdlib\Exception\InvalidArgumentException;
use LaminasTest\Stdlib\TestAsset\GuardedObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Laminas\Stdlib\Guard\ArrayOrTraversableGuardTrait
 */
class ArrayOrTraversableGuardTraitTest extends TestCase
{
    public function testGuardForArrayOrTraversableThrowsException(): void
    {
        $object = new GuardedObject();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Argument must be an array or Traversable, [string] given');
        $object->setArrayOrTraversable('');
    }

    public function testGuardForArrayOrTraversableAllowsArray(): void
    {
        $object = new GuardedObject();
        self::assertNull($object->setArrayOrTraversable([]));
    }

    public function testGuardForArrayOrTraversableAllowsTraversable(): void
    {
        $object      = new GuardedObject();
        $traversable = new arrayobject();
        self::assertNull($object->setArrayOrTraversable($traversable));
    }
}
