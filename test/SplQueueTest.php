<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\SplQueue;
use PHPUnit\Framework\TestCase;

/**
 * @group      Laminas_Stdlib
 */
class SplQueueTest extends TestCase
{
    /**
     * @var SplQueue
     */
    protected $queue;

    public function setUp()
    {
        $this->queue = new SplQueue();
        $this->queue->push('foo');
        $this->queue->push('bar');
        $this->queue->push('baz');
    }

    public function testSerializationAndDeserializationShouldMaintainState()
    {
        $s = serialize($this->queue);
        $unserialized = unserialize($s);
        $count = count($this->queue);
        $this->assertSame($count, count($unserialized));

        $expected = iterator_to_array($this->queue);
        $test = iterator_to_array($unserialized);
        $this->assertSame($expected, $test);
    }

    public function testCanRetrieveQueueAsArray()
    {
        $expected = ['foo', 'bar', 'baz'];
        $this->assertSame($expected, $this->queue->toArray());
    }
}
