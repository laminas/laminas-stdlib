<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\Guard;

use LaminasTest\Stdlib\TestAsset\GuardedObject;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @requires PHP 5.4
 * @covers   Laminas\Stdlib\Guard\EmptyGuardTrait
 */
class EmptyGuardTraitTest extends TestCase
{
    public function setUp()
    {
        if (version_compare(PHP_VERSION, '5.4.0', '<')) {
            $this->markTestSkipped('Only valid for php >= 5.4');
        }
    }

    public function testGuardAgainstEmptyThrowsException()
    {
        $object = new GuardedObject;
        $this->setExpectedException(
            'Laminas\Stdlib\Exception\InvalidArgumentException',
            'Argument cannot be empty'
        );
        $object->setNotEmpty('');
    }

    public function testGuardAgainstEmptyAllowsNonEmptyString()
    {
        $object = new GuardedObject;
        $this->assertNull($object->setNotEmpty('foo'));
    }
}
