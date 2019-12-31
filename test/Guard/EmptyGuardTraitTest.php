<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\Guard;

use Laminas\Stdlib\Exception\InvalidArgumentException;
use LaminasTest\Stdlib\TestAsset\GuardedObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers   Laminas\Stdlib\Guard\EmptyGuardTrait
 */
class EmptyGuardTraitTest extends TestCase
{
    public function testGuardAgainstEmptyThrowsException()
    {
        $object = new GuardedObject;
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Argument cannot be empty');
        $object->setNotEmpty('');
    }

    public function testGuardAgainstEmptyAllowsNonEmptyString()
    {
        $object = new GuardedObject;
        $this->assertNull($object->setNotEmpty('foo'));
    }
}
