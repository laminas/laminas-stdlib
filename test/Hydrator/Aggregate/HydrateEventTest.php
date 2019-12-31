<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\Hydrator\Aggregate;

use Laminas\Stdlib\Hydrator\Aggregate\AggregateHydrator;
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
        $data1     = array('president' => 'Zaphod');
        $event     = new HydrateEvent($target, $hydrated1, $data1);
        $data2     = array('maintainer' => 'Marvin');
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
