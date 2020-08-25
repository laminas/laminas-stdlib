<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\SplPriorityQueue;
use PHPUnit\Framework\TestCase;

use function array_values;
use function iterator_to_array;
use function serialize;
use function unserialize;
use function var_export;

/**
 * @group      Laminas_Stdlib
 */
class SplPriorityQueueTest extends TestCase
{
    /**
     * @var SplPriorityQueue
     */
    protected $queue;

    protected function setUp() : void
    {
        $this->queue = new SplPriorityQueue();
        $this->queue->insert('foo', 3);
        $this->queue->insert('bar', 4);
        $this->queue->insert('baz', 2);
        $this->queue->insert('bat', 1);
    }

    public function testMaintainsInsertOrderForDataOfEqualPriority()
    {
        $queue = new SplPriorityQueue();
        $queue->insert('foo', 1000);
        $queue->insert('bar', 1000);
        $queue->insert('baz', 1000);
        $queue->insert('bat', 1000);

        $expected = ['foo', 'bar', 'baz', 'bat'];
        $test = array_values(iterator_to_array($queue));
        self::assertEquals($expected, $test);
    }

    public function testSerializationAndDeserializationShouldMaintainState()
    {
        $s = serialize($this->queue);
        $unserialized = unserialize($s);

        // assert same size
        self::assertSameSize($this->queue, $unserialized);

        // assert same values
        self::assertSame(iterator_to_array($this->queue), iterator_to_array($unserialized));

        // assert equal
        self::assertEquals($this->queue, $unserialized);
    }

    public function testCanRetrieveQueueAsArray()
    {
        $expected = [
            'bar',
            'foo',
            'baz',
            'bat',
        ];
        $test     = $this->queue->toArray();
        self::assertSame($expected, $test, var_export($test, 1));
    }
}
