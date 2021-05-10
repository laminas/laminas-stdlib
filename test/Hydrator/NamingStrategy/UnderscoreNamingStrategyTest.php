<?php

namespace LaminasTest\Stdlib\Hydrator\NamingStrategy;

use Laminas\Stdlib\Hydrator\NamingStrategy\UnderscoreNamingStrategy;

/**
 * Unit tests for {@see \Laminas\Stdlib\Hydrator\NamingStrategy\UnderscoreNamingStrategy}
 *
 * @covers \Laminas\Stdlib\Hydrator\NamingStrategy\UnderscoreNamingStrategy
 */
class UnderscoreNamingStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testNameHydratesToCamelCase()
    {
        $strategy = new UnderscoreNamingStrategy();
        $this->assertEquals('fooBarBaz', $strategy->hydrate('foo_bar_baz'));
    }

    public function testNameExtractsToUnderscore()
    {
        $strategy = new UnderscoreNamingStrategy();
        $this->assertEquals('foo_bar_baz', $strategy->extract('fooBarBaz'));
    }

    /**
     * @group 6422
     * @group 6420
     */
    public function testNameHydratesToStudlyCaps()
    {
        $strategy = new UnderscoreNamingStrategy();

        $this->assertEquals('fooBarBaz', $strategy->hydrate('Foo_Bar_Baz'));
    }
}
