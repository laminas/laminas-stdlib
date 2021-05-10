<?php

namespace LaminasTest\Stdlib\Hydrator\NamingStrategy;

use Laminas\Stdlib\Hydrator\NamingStrategy\MapNamingStrategy;

class MapNamingStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testHydrateMap()
    {
        $namingStrategy = new MapNamingStrategy(['foo' => 'bar']);

        $this->assertEquals('bar', $namingStrategy->hydrate('foo'));
        $this->assertEquals('foo', $namingStrategy->extract('bar'));
    }

    public function testHydrateAndExtractMaps()
    {
        $namingStrategy = new MapNamingStrategy(
            ['foo' => 'foo-hydrated'],
            ['bar' => 'bar-extracted']
        );

        $this->assertEquals('foo-hydrated', $namingStrategy->hydrate('foo'));
        $this->assertEquals('bar-extracted', $namingStrategy->extract('bar'));
    }

    public function testSingleMapInvalidValue()
    {
        $this->setExpectedException('InvalidArgumentException');
        new MapNamingStrategy(['foo' => 3.1415]);
    }
}
