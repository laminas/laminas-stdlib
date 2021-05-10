<?php

namespace LaminasTest\Stdlib\Hydrator\NamingStrategy;

use Laminas\Stdlib\Hydrator\NamingStrategy\CompositeNamingStrategy;
use Laminas\Stdlib\Hydrator\NamingStrategy\NamingStrategyInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Laminas\Stdlib\Hydrator\NamingStrategy\CompositeNamingStrategy
 */
class CompositeNamingStrategyTest extends TestCase
{
    public function testGetSameNameWhenNoNamingStrategyExistsForTheName()
    {
        $compositeNamingStrategy = new CompositeNamingStrategy([
            'foo' => $this->createMock(NamingStrategyInterface::class)
        ]);

        $this->assertEquals('bar', $compositeNamingStrategy->hydrate('bar'));
        $this->assertEquals('bar', $compositeNamingStrategy->extract('bar'));
    }

    public function testUseDefaultNamingStrategy()
    {
        /* @var $defaultNamingStrategy NamingStrategyInterface|\PHPUnit_Framework_MockObject_MockObject*/
        $defaultNamingStrategy = $this->createMock(NamingStrategyInterface::class);
        $defaultNamingStrategy->expects($this->at(0))
            ->method('hydrate')
            ->with('foo')
            ->will($this->returnValue('Foo'));
        $defaultNamingStrategy->expects($this->at(1))
            ->method('extract')
            ->with('Foo')
            ->will($this->returnValue('foo'));

        $compositeNamingStrategy = new CompositeNamingStrategy(
            ['bar' => $this->createMock(NamingStrategyInterface::class)],
            $defaultNamingStrategy
        );
        $this->assertEquals('Foo', $compositeNamingStrategy->hydrate('foo'));
        $this->assertEquals('foo', $compositeNamingStrategy->extract('Foo'));
    }

    public function testHydrate()
    {
        $fooNamingStrategy = $this->createMock(NamingStrategyInterface::class);
        $fooNamingStrategy->expects($this->once())
            ->method('hydrate')
            ->with('foo')
            ->will($this->returnValue('FOO'));
        $compositeNamingStrategy = new CompositeNamingStrategy(['foo' => $fooNamingStrategy]);
        $this->assertEquals('FOO', $compositeNamingStrategy->hydrate('foo'));
    }

    public function testExtract()
    {
        $fooNamingStrategy = $this->createMock(NamingStrategyInterface::class);
        $fooNamingStrategy->expects($this->once())
            ->method('extract')
            ->with('FOO')
            ->will($this->returnValue('foo'));
        $compositeNamingStrategy = new CompositeNamingStrategy(['FOO' => $fooNamingStrategy]);
        $this->assertEquals('foo', $compositeNamingStrategy->extract('FOO'));
    }
}
