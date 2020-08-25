<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\SplQueue;
use PHPUnit\Framework\TestCase;

use function count;
use function iterator_to_array;
use function serialize;
use function unserialize;

/**
 * @group      Laminas_Stdlib
 */
class SplQueueTest extends TestCase
{
    /**
     * @var SplQueue
     */
    protected $queue;

    protected function setUp() : void
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
        self::assertSame($count, count($unserialized));

        $expected = iterator_to_array($this->queue);
        $test = iterator_to_array($unserialized);
        self::assertSame($expected, $test);
    }

    public function testCanRetrieveQueueAsArray()
    {
        $expected = ['foo', 'bar', 'baz'];
        self::assertSame($expected, $this->queue->toArray());
    }
}
