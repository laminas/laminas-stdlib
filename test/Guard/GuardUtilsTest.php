<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\Guard;

use Laminas\Stdlib\ArrayObject;
use Laminas\Stdlib\Guard\GuardUtils;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers   Laminas\Stdlib\Guard\GuardUtils
 */
class GuardUtilsTest extends TestCase
{
    public function testGuardForArrayOrTraversableThrowsException()
    {
        $this->setExpectedException(
            'Laminas\Stdlib\Exception\InvalidArgumentException',
            'Argument must be an array or Traversable, [string] given'
        );
        GuardUtils::guardForArrayOrTraversable('');
    }

    public function testGuardForArrayOrTraversableAllowsArray()
    {
        $this->assertNull(GuardUtils::guardForArrayOrTraversable(array()));
    }

    public function testGuardForArrayOrTraversableAllowsTraversable()
    {
        $traversable = new ArrayObject;
        $this->assertNull(GuardUtils::guardForArrayOrTraversable($traversable));
    }

    public function testGuardAgainstEmptyThrowsException()
    {
        $this->setExpectedException(
            'Laminas\Stdlib\Exception\InvalidArgumentException',
            'Argument cannot be empty'
        );
        GuardUtils::guardAgainstEmpty('');
    }

    public function testGuardAgainstEmptyAllowsNonEmptyString()
    {
        $this->assertNull(GuardUtils::guardAgainstEmpty('foo'));
    }

    public function testGuardAgainstNullThrowsException()
    {
        $this->setExpectedException(
            'Laminas\Stdlib\Exception\InvalidArgumentException',
            'Argument cannot be null'
        );
        GuardUtils::guardAgainstNull(null);
    }

    public function testGuardAgainstNullAllowsNonNull()
    {
        $this->assertNull(GuardUtils::guardAgainstNull('foo'));
    }
}
