<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\SplPriorityQueue;

/**
 * @group      Laminas_Stdlib
 */
class SplPriorityQueueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SplPriorityQueue
     */
    protected $queue;

    public function setUp()
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
        $this->assertEquals($expected, $test);
    }

    public function testSerializationAndDeserializationShouldMaintainState()
    {
        $s = serialize($this->queue);
        $unserialized = unserialize($s);
        $count = count($this->queue);
        $this->assertSame(
            $count,
            count($unserialized),
            'Expected count ' . $count . '; received ' . count($unserialized)
        );

        $expected = iterator_to_array($this->queue);
        $test = iterator_to_array($unserialized);
        $this->assertSame(
            $expected,
            $test,
            'Expected: ' . var_export($expected, 1) . "\nReceived:" . var_export($test, 1)
        );
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
        $this->assertSame($expected, $test, var_export($test, 1));
    }
}
