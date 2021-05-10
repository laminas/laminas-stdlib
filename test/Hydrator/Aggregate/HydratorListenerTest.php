<?php

namespace LaminasTest\Stdlib\Hydrator\Aggregate;

use Laminas\EventManager\EventManagerInterface;
use Laminas\Stdlib\Hydrator\Aggregate\ExtractEvent;
use Laminas\Stdlib\Hydrator\Aggregate\HydrateEvent;
use Laminas\Stdlib\Hydrator\Aggregate\HydratorListener;
use Laminas\Stdlib\Hydrator\HydratorInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Unit tests for {@see \Laminas\Stdlib\Hydrator\Aggregate\HydratorListener}
 */
class HydratorListenerTest extends TestCase
{
    /**
     * @var \Laminas\Stdlib\Hydrator\HydratorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $hydrator;

    /**
     * @var \Laminas\Stdlib\Hydrator\Aggregate\HydratorListener
     */
    protected $listener;

    /**
     * {@inheritDoc}
     *
     * @covers \Laminas\Stdlib\Hydrator\Aggregate\HydratorListener::__construct
     */
    public function setUp()
    {
        $this->hydrator = $this->createMock(HydratorInterface::class);
        $this->listener = new HydratorListener($this->hydrator);
    }

    /**
     * @covers \Laminas\Stdlib\Hydrator\Aggregate\HydratorListener::attach
     */
    public function testAttach()
    {
        $eventManager = $this->createMock(EventManagerInterface::class);

        $eventManager
            ->expects($this->exactly(2))
            ->method('attach')
            ->with(
                $this->logicalOr(HydrateEvent::EVENT_HYDRATE, ExtractEvent::EVENT_EXTRACT),
                $this->logicalAnd(
                    $this->callback('is_callable'),
                    $this->logicalOr([$this->listener, 'onHydrate'], [$this->listener, 'onExtract'])
                )
            );

        $this->listener->attach($eventManager);
    }

    /**
     * @covers \Laminas\Stdlib\Hydrator\Aggregate\HydratorListener::onHydrate
     */
    public function testOnHydrate()
    {
        $object   = new stdClass();
        $hydrated = new stdClass();
        $data     = ['foo' => 'bar'];
        $event    = $this->createMock(HydrateEvent::class);

        $event->expects($this->any())->method('getHydratedObject')->will($this->returnValue($object));
        $event->expects($this->any())->method('getHydrationData')->will($this->returnValue($data));

        $this
            ->hydrator
            ->expects($this->once())
            ->method('hydrate')
            ->with($data, $object)
            ->will($this->returnValue($hydrated));
        $event->expects($this->once())->method('setHydratedObject')->with($hydrated);

        $this->assertSame($hydrated, $this->listener->onHydrate($event));
    }

    /**
     * @covers \Laminas\Stdlib\Hydrator\Aggregate\HydratorListener::onExtract
     */
    public function testOnExtract()
    {
        $object = new stdClass();
        $data   = ['foo' => 'bar'];
        $event  = $this
            ->getMockBuilder('Laminas\Stdlib\Hydrator\Aggregate\ExtractEvent')
            ->disableOriginalConstructor()
            ->getMock();


        $event->expects($this->any())->method('getExtractionObject')->will($this->returnValue($object));

        $this
            ->hydrator
            ->expects($this->once())
            ->method('extract')
            ->with($object)
            ->will($this->returnValue($data));
        $event->expects($this->once())->method('mergeExtractedData')->with($data);

        $this->assertSame($data, $this->listener->onExtract($event));
    }
}
