<?php

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
        $strategy = new ArrayMapNamingStrategy([]);
        $this->assertEquals('some_stuff', $strategy->hydrate('some_stuff'));
        $this->assertEquals('some_stuff', $strategy->extract('some_stuff'));
    }

    public function testExtract()
    {
        $strategy = new ArrayMapNamingStrategy(['stuff3' => 'stuff4']);
        $this->assertEquals('stuff4', $strategy->extract('stuff3'));
    }

    public function testHydrate()
    {
        $strategy = new ArrayMapNamingStrategy(['foo' => 'bar']);
        $this->assertEquals('foo', $strategy->hydrate('bar'));
    }
}
