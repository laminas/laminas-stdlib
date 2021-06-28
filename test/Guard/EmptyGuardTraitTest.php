<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\Guard;

use Laminas\Stdlib\Exception\InvalidArgumentException;
use LaminasTest\Stdlib\TestAsset\GuardedObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Laminas\Stdlib\Guard\EmptyGuardTrait
 */
class EmptyGuardTraitTest extends TestCase
{
    public function testGuardAgainstEmptyThrowsException()
    {
        $object = new GuardedObject();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Argument cannot be empty');
        $object->setNotEmpty('');
    }

    public function testGuardAgainstEmptyAllowsNonEmptyString()
    {
        $object = new GuardedObject();
        self::assertNull($object->setNotEmpty('foo'));
    }
}
