<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\Guard;

use Laminas\Stdlib\Exception\InvalidArgumentException;
use LaminasTest\Stdlib\TestAsset\GuardedObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Laminas\Stdlib\Guard\NullGuardTrait
 */
class NullGuardTraitTest extends TestCase
{
    public function testGuardAgainstNullThrowsException()
    {
        $object = new GuardedObject();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Argument cannot be null');

        $object->setNotNull(null);
    }

    public function testGuardAgainstNullAllowsNonNull()
    {
        $object = new GuardedObject();
        self::assertNull($object->setNotNull('foo'));
    }
}
