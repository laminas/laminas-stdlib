<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\Hydrator\NamingStrategy;

use Laminas\Stdlib\Hydrator\NamingStrategy\CompositeNamingStrategy;
use Laminas\Stdlib\Hydrator\NamingStrategy\NamingStrategyInterface;

/**
 * Tests for {@see \Laminas\Stdlib\Hydrator\NamingStrategy\CompositeNamingStrategy}
 *
 * @covers \Laminas\Stdlib\Hydrator\NamingStrategy\CompositeNamingStrategy
 */
class CompositeNamingStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSameNameWhenNoNamingStrategyExistsForTheName()
    {
        $compositeNamingStrategy = new CompositeNamingStrategy([
            'foo' => $this->getMock('Laminas\Stdlib\Hydrator\NamingStrategy\NamingStrategyInterface')
        ]);

        $this->assertEquals('bar', $compositeNamingStrategy->hydrate('bar'));
        $this->assertEquals('bar', $compositeNamingStrategy->extract('bar'));
    }

    public function testUseDefaultNamingStrategy()
    {
        /* @var $defaultNamingStrategy NamingStrategyInterface|\PHPUnit_Framework_MockObject_MockObject*/
        $defaultNamingStrategy = $this->getMock('Laminas\Stdlib\Hydrator\NamingStrategy\NamingStrategyInterface');
        $defaultNamingStrategy->expects($this->at(0))
            ->method('hydrate')
            ->with('foo')
            ->will($this->returnValue('Foo'));
        $defaultNamingStrategy->expects($this->at(1))
            ->method('extract')
            ->with('Foo')
            ->will($this->returnValue('foo'));

        $compositeNamingStrategy = new CompositeNamingStrategy(
            ['bar' => $this->getMock('Laminas\Stdlib\Hydrator\NamingStrategy\NamingStrategyInterface')],
            $defaultNamingStrategy
        );
        $this->assertEquals('Foo', $compositeNamingStrategy->hydrate('foo'));
        $this->assertEquals('foo', $compositeNamingStrategy->extract('Foo'));
    }

    public function testHydrate()
    {
        $fooNamingStrategy = $this->getMock('Laminas\Stdlib\Hydrator\NamingStrategy\NamingStrategyInterface');
        $fooNamingStrategy->expects($this->once())
            ->method('hydrate')
            ->with('foo')
            ->will($this->returnValue('FOO'));
        $compositeNamingStrategy = new CompositeNamingStrategy(['foo' => $fooNamingStrategy]);
        $this->assertEquals('FOO', $compositeNamingStrategy->hydrate('foo'));
    }

    public function testExtract()
    {
        $fooNamingStrategy = $this->getMock('Laminas\Stdlib\Hydrator\NamingStrategy\NamingStrategyInterface');
        $fooNamingStrategy->expects($this->once())
            ->method('extract')
            ->with('FOO')
            ->will($this->returnValue('foo'));
        $compositeNamingStrategy = new CompositeNamingStrategy(['FOO' => $fooNamingStrategy]);
        $this->assertEquals('foo', $compositeNamingStrategy->extract('FOO'));
    }
}
