<?php

namespace LaminasTest\Stdlib\Hydrator\Aggregate;

use Laminas\Stdlib\Hydrator\Aggregate\HydrateEvent;
use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * Unit tests for {@see \Laminas\Stdlib\Hydrator\Aggregate\HydrateEvent}
 */
class HydrateEventTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \Laminas\Stdlib\Hydrator\Aggregate\HydrateEvent
     */
    public function testEvent()
    {
        $target    = new stdClass();
        $hydrated1 = new stdClass();
        $data1     = ['president' => 'Zaphod'];
        $event     = new HydrateEvent($target, $hydrated1, $data1);
        $data2     = ['maintainer' => 'Marvin'];
        $hydrated2 = new stdClass();

        $this->assertSame(HydrateEvent::EVENT_HYDRATE, $event->getName());
        $this->assertSame($target, $event->getTarget());
        $this->assertSame($hydrated1, $event->getHydratedObject());
        $this->assertSame($data1, $event->getHydrationData());

        $event->setHydrationData($data2);

        $this->assertSame($data2, $event->getHydrationData());


        $event->setHydratedObject($hydrated2);

        $this->assertSame($hydrated2, $event->getHydratedObject());
    }
}
