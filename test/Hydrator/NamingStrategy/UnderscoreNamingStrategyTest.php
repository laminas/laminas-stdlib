<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

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
}
