<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\Hydrator\NamingStrategy;

use Laminas\Stdlib\Hydrator\NamingStrategy\IdentityNamingStrategy;

/**
 * Tests for {@see \Laminas\Stdlib\Hydrator\NamingStrategy\IdentityNamingStrategy}
 *
 * @covers \Laminas\Stdlib\Hydrator\NamingStrategy\IdentityNamingStrategy
 */
class IdentityNamingStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getTestedNames
     *
     * @param string $name
     */
    public function testHydrate($name)
    {
        $namingStrategy = new IdentityNamingStrategy();

        $this->assertSame($name, $namingStrategy->hydrate($name));
    }

    /**
     * @dataProvider getTestedNames
     *
     * @param string $name
     */
    public function testExtract($name)
    {
        $namingStrategy = new IdentityNamingStrategy();

        $this->assertSame($name, $namingStrategy->extract($name));
    }

    /**
     * Data provider
     *
     * @return string[][]
     */
    public function getTestedNames()
    {
        return array(
            array(123),
            array(0),
            array('foo'),
            array('bar'),
        );
    }
}
