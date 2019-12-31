<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\Hydrator\Aggregate;

use Laminas\Stdlib\Hydrator\Aggregate\ExtractEvent;
use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * Unit tests for {@see \Laminas\Stdlib\Hydrator\Aggregate\ExtractEvent}
 */
class ExtractEventTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \Laminas\Stdlib\Hydrator\Aggregate\ExtractEvent
     */
    public function testEvent()
    {
        $target    = new stdClass();
        $object1   = new stdClass();
        $event     = new ExtractEvent($target, $object1);
        $data2     = ['maintainer' => 'Marvin'];
        $object2   = new stdClass();

        $this->assertSame(ExtractEvent::EVENT_EXTRACT, $event->getName());
        $this->assertSame($target, $event->getTarget());
        $this->assertSame($object1, $event->getExtractionObject());
        $this->assertSame([], $event->getExtractedData());

        $event->setExtractedData($data2);

        $this->assertSame($data2, $event->getExtractedData());


        $event->setExtractionObject($object2);

        $this->assertSame($object2, $event->getExtractionObject());

        $event->mergeExtractedData(['president' => 'Zaphod']);

        $extracted = $event->getExtractedData();

        $this->assertCount(2, $extracted);
        $this->assertSame('Marvin', $extracted['maintainer']);
        $this->assertSame('Zaphod', $extracted['president']);
    }
}
