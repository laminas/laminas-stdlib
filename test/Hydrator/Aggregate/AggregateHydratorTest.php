<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\Hydrator\Aggregate;

use Laminas\EventManager\EventManager;
use Laminas\Hydrator\Aggregate\HydratorListener;
use Laminas\Hydrator\HydratorInterface;
use Laminas\Stdlib\Hydrator\Aggregate\AggregateHydrator;
use Laminas\Stdlib\Hydrator\Aggregate\ExtractEvent;
use Laminas\Stdlib\Hydrator\Aggregate\HydrateEvent;
use PHPUnit_Framework_TestCase;
use Prophecy\Argument;
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
     * @var \Laminas\EventManager\EventManager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $eventManager;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->eventManager = $this->getMock(EventManager::class);
        $this->hydrator     = new AggregateHydrator();

        $this->hydrator->setEventManager($this->eventManager);
    }

    /**
     * @covers \Laminas\Stdlib\Hydrator\Aggregate\AggregateHydrator::add
     */
    public function testAdd()
    {
        $hydrator = $this->prophesize(HydratorInterface::class)->reveal();

        $events = $this->prophesize(EventManager::class);

        $events->setIdentifiers(Argument::type('array'))->shouldBeCalled();

        $events->attach(
            HydrateEvent::EVENT_HYDRATE,
            Argument::that(function ($argument) {
                if (! is_callable($argument)) {
                    return false;
                }
                if (! is_array($argument)) {
                    return false;
                }
                return (
                    $argument[0] instanceof HydratorListener
                    && $argument[1] === 'onHydrate'
                );
            }),
            123
        )->shouldBeCalled();

        $events->attach(
            ExtractEvent::EVENT_EXTRACT,
            Argument::that(function ($argument) {
                if (! is_callable($argument)) {
                    return false;
                }
                if (! is_array($argument)) {
                    return false;
                }
                return (
                    $argument[0] instanceof HydratorListener
                    && $argument[1] === 'onExtract'
                );
            }),
            123
        )->shouldBeCalled();

        $this->hydrator->setEventManager($events->reveal());
        $this->hydrator->add($hydrator, 123);
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
            ->method('triggerEvent')
            ->with($this->isInstanceOf('Laminas\Hydrator\Aggregate\HydrateEvent'));

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
            ->method('triggerEvent')
            ->with($this->isInstanceOf('Laminas\Hydrator\Aggregate\ExtractEvent'));

        $this->assertSame([], $this->hydrator->extract($object));
    }

    /**
     * @group 55
     * @covers \Laminas\Stdlib\Hydrator\Aggregate\AggregateHydrator::hydrate
     */
    public function testHydrateUsesStdlibHydrateEvent()
    {
        $object = new stdClass();

        $this
            ->eventManager
            ->expects($this->once())
            ->method('triggerEvent')
            ->with($this->isInstanceOf('Laminas\Stdlib\Hydrator\Aggregate\HydrateEvent'));

        $this->assertSame($object, $this->hydrator->hydrate(['foo' => 'bar'], $object));
    }

    /**
     * @group 55
     * @covers \Laminas\Stdlib\Hydrator\Aggregate\AggregateHydrator::extract
     */
    public function testExtractUsesStdlibExtractEvent()
    {
        $object = new stdClass();

        $this
            ->eventManager
            ->expects($this->once())
            ->method('triggerEvent')
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
                     'Laminas\Hydrator\Aggregate\AggregateHydrator',
                     'Laminas\Stdlib\Hydrator\Aggregate\AggregateHydrator',
                ]
            );

        $hydrator->setEventManager($eventManager);

        $this->assertSame($eventManager, $hydrator->getEventManager());
    }
}
