<?php

namespace LaminasTest\Stdlib\Guard;

use Laminas\Stdlib\ArrayObject;
use LaminasTest\Stdlib\TestAsset\GuardedObject;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @requires PHP 5.4
 * @covers   Laminas\Stdlib\Guard\ArrayOrTraversableGuardTrait
 */
class ArrayOrTraversableGuardTraitTest extends TestCase
{
    public function setUp()
    {
        if (version_compare(PHP_VERSION, '5.4.0', '<')) {
            $this->markTestSkipped('Only valid for php >= 5.4');
        }
    }

    public function testGuardForArrayOrTraversableThrowsException()
    {
        $object = new GuardedObject;
        $this->setExpectedException(
            'Laminas\Stdlib\Exception\InvalidArgumentException',
            'Argument must be an array or Traversable, [string] given'
        );
        $object->setArrayOrTraversable('');
    }

    public function testGuardForArrayOrTraversableAllowsArray()
    {
        $object = new GuardedObject;
        $this->assertNull($object->setArrayOrTraversable([]));
    }

    public function testGuardForArrayOrTraversableAllowsTraversable()
    {
        $object      = new GuardedObject;
        $traversable = new ArrayObject;
        $this->assertNull($object->setArrayOrTraversable($traversable));
    }
}
