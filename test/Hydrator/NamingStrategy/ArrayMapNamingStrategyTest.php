<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\Hydrator\NamingStrategy;

use Laminas\Stdlib\Hydrator\NamingStrategy\ArrayMapNamingStrategy;

/**
 * Tests for {@see \Laminas\Stdlib\Hydrator\NamingStrategy\ArrayMapNamingStrategy}
 *
 * @covers \Laminas\Stdlib\Hydrator\NamingStrategy\ArrayMapNamingStrategy
 */
class ArrayMapNamingStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSameNameWithEmptyMap()
    {
        $strategy = new ArrayMapNamingStrategy(array());
        $this->assertEquals('some_stuff', $strategy->hydrate('some_stuff'));
        $this->assertEquals('some_stuff', $strategy->extract('some_stuff'));
    }

    public function testExtract()
    {
        $strategy = new ArrayMapNamingStrategy(array('stuff3' => 'stuff4'));
        $this->assertEquals('stuff4', $strategy->extract('stuff3'));
    }

    public function testHydrate()
    {
        $strategy = new ArrayMapNamingStrategy(array('foo' => 'bar'));
        $this->assertEquals('foo', $strategy->hydrate('bar'));
    }
}
