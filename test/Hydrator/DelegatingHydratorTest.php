<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\Hydrator;

use ArrayObject;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\Stdlib\Hydrator\DelegatingHydrator;
use Laminas\Stdlib\Hydrator\HydratorInterface;

/**
 * Unit tests for {@see \Laminas\Stdlib\Hydrator\DelegatingHydrator}
 *
 * @covers \Laminas\Stdlib\Hydrator\DelegatingHydrator
 */
class DelegatingHydratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DelegatingHydrator
     */
    protected $hydrator;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $hydrators;

    /**
     * @var ArrayObject
     */
    protected $object;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->hydrators = $this->prophesize(ServiceLocatorInterface::class);
        $this->hydrators->willImplement(ContainerInterface::class);
        $this->hydrator = new DelegatingHydrator($this->hydrators->reveal());
        $this->object = new ArrayObject;
    }

    public function testExtract()
    {
        $hydrator = $this->prophesize(HydratorInterface::class);
        $hydrator->extract($this->object)->willReturn(['foo' => 'bar']);

        $this->hydrators->has(ArrayObject::class)->willReturn(true);
        $this->hydrators->get(ArrayObject::class)->willReturn($hydrator->reveal());

        $this->assertEquals(['foo' => 'bar'], $this->hydrator->extract($this->object));
    }

    public function testHydrate()
    {
        $hydrator = $this->prophesize(HydratorInterface::class);
        $hydrator->hydrate(['foo' => 'bar'], $this->object)->willReturn($this->object);

        $this->hydrators->has(ArrayObject::class)->willReturn(true);
        $this->hydrators->get(ArrayObject::class)->willReturn($hydrator->reveal());

        $this->assertEquals($this->object, $this->hydrator->hydrate(['foo' => 'bar'], $this->object));
    }
}
