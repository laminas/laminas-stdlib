<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\Hydrator\NamingStrategy;

use Laminas\Stdlib\Hydrator\NamingStrategy\MapNamingStrategy;

class MapNamingStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testHydrateMap()
    {
        $namingStrategy = new MapNamingStrategy(array('foo' => 'bar'));

        $this->assertEquals('bar', $namingStrategy->hydrate('foo'));
        $this->assertEquals('foo', $namingStrategy->extract('bar'));
    }

    public function testHydrateAndExtractMaps()
    {
        $namingStrategy = new MapNamingStrategy(
            array('foo' => 'foo-hydrated'),
            array('bar' => 'bar-extracted')
        );

        $this->assertEquals('foo-hydrated', $namingStrategy->hydrate('foo'));
        $this->assertEquals('bar-extracted', $namingStrategy->extract('bar'));
    }

    public function testSingleMapInvalidValue()
    {
        $this->setExpectedException('InvalidArgumentException');
        new MapNamingStrategy(array('foo' => 3.1415));
    }
}
