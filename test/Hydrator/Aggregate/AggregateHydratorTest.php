<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\Hydrator\Aggregate;

use Laminas\Stdlib\Hydrator\Aggregate\AggregateHydrator;
use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * Unit tests for {@see \Laminas\Stdlib\Hydrator\Aggregate\AggregateHydrator}
 */
class AggregateHydratorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Laminas\Stdlib\Hydrator\Aggregate\AggregateHydrator
     */
    protected $hydrator;

    /**
     * @var \Laminas\EventManager\EventManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $eventManager;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->eventManager = $this->getMock('Laminas\EventManager\EventManagerInterface');
        $this->hydrator     = new AggregateHydrator();

        $this->hydrator->setEventManager($this->eventManager);
    }

    /**
     * @covers \Laminas\Stdlib\Hydrator\Aggregate\AggregateHydrator::add
     */
    public function testAdd()
    {
        $attached = $this->getMock('Laminas\Stdlib\Hydrator\HydratorInterface');

        $this
            ->eventManager
            ->expects($this->once())
            ->method('attachAggregate')
            ->with($this->isInstanceOf('Laminas\Stdlib\Hydrator\Aggregate\HydratorListener'), 123);

        $this->hydrator->add($attached, 123);
    }

    /**
     * @covers \Laminas\Stdlib\Hydrator\Aggregate\AggregateHydrator::hydrate
     */
    public function testHydrate()
    {
        $object = new stdClass();

        $this
            ->eventManager
            ->expects($this->once())
            ->method('trigger')
            ->with($this->isInstanceOf('Laminas\Stdlib\Hydrator\Aggregate\HydrateEvent'));

        $this->assertSame($object, $this->hydrator->hydrate(['foo' => 'bar'], $object));
    }

    /**
     * @covers \Laminas\Stdlib\Hydrator\Aggregate\AggregateHydrator::extract
     */
    public function testExtract()
    {
        $object = new stdClass();

        $this
            ->eventManager
            ->expects($this->once())
            ->method('trigger')
            ->with($this->isInstanceOf('Laminas\Stdlib\Hydrator\Aggregate\ExtractEvent'));

        $this->assertSame([], $this->hydrator->extract($object));
    }

    /**
     * @covers \Laminas\Stdlib\Hydrator\Aggregate\AggregateHydrator::getEventManager
     * @covers \Laminas\Stdlib\Hydrator\Aggregate\AggregateHydrator::setEventManager
     */
    public function testGetSetManager()
    {
        $hydrator     = new AggregateHydrator();
        $eventManager = $this->getMock('Laminas\EventManager\EventManagerInterface');

        $this->assertInstanceOf('Laminas\EventManager\EventManagerInterface', $hydrator->getEventManager());

        $eventManager
            ->expects($this->once())
            ->method('setIdentifiers')
            ->with(
                [
                     'Laminas\Stdlib\Hydrator\Aggregate\AggregateHydrator',
                     'Laminas\Stdlib\Hydrator\Aggregate\AggregateHydrator',
                ]
            );

        $hydrator->setEventManager($eventManager);

        $this->assertSame($eventManager, $hydrator->getEventManager());
    }
}
