<?php

namespace LaminasTest\Stdlib\Guard;

use LaminasTest\Stdlib\TestAsset\GuardedObject;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @requires PHP 5.4
 * @covers   Laminas\Stdlib\Guard\NullGuardTrait
 */
class NullGuardTraitTest extends TestCase
{
    public function setUp()
    {
        if (version_compare(PHP_VERSION, '5.4.0', '<')) {
            $this->markTestSkipped('Only valid for php >= 5.4');
        }
    }

    public function testGuardAgainstNullThrowsException()
    {
        $object = new GuardedObject;
        $this->setExpectedException(
            'Laminas\Stdlib\Exception\InvalidArgumentException',
            'Argument cannot be null'
        );
        $object->setNotNull(null);
    }

    public function testGuardAgainstNullAllowsNonNull()
    {
        $object = new GuardedObject;
        $this->assertNull($object->setNotNull('foo'));
    }
}
