<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\SplPriorityQueue;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Traversable;

use function array_values;
use function iterator_to_array;
use function serialize;
use function unserialize;
use function var_export;

use const PHP_INT_MAX;

#[Group('Laminas_Stdlib')]
class SplPriorityQueueTest extends TestCase
{
    /** @var SplPriorityQueue<string, int> */
    private SplPriorityQueue $queue;

    protected function setUp(): void
    {
        $this->queue = new SplPriorityQueue();
        $this->queue->insert('foo', 3);
        $this->queue->insert('bar', 4);
        $this->queue->insert('baz', 2);
        $this->queue->insert('bat', 1);
    }

    public function testMaintainsInsertOrderForDataOfEqualPriority(): void
    {
        $queue = new SplPriorityQueue();
        $queue->insert('foo', 1000);
        $queue->insert('bar', 1000);
        $queue->insert('baz', 1000);
        $queue->insert('bat', 1000);

        $expected = ['foo', 'bar', 'baz', 'bat'];
        $test     = array_values(iterator_to_array($queue));
        self::assertEquals($expected, $test);
    }

    public function testSerializationAndDeserializationShouldMaintainState(): void
    {
        $s            = serialize($this->queue);
        $unserialized = unserialize($s);
        self::assertInstanceOf(Traversable::class, $unserialized);

        // assert same size
        self::assertSameSize($this->queue, $unserialized);

        // assert same values
        self::assertSame(iterator_to_array($this->queue), iterator_to_array($unserialized));

        // assert equal
        self::assertEquals($this->queue, $unserialized);
    }

    public function testCanRetrieveQueueAsArray(): void
    {
        $expected = [
            'bar',
            'foo',
            'baz',
            'bat',
        ];
        $test     = $this->queue->toArray();
        self::assertSame($expected, $test, var_export($test, true));
    }

    public function testThatToArrayWithExtractBothYieldsExpectedValues(): void
    {
        $queue = new SplPriorityQueue();
        $queue->insert('foo', 10);
        $queue->setExtractFlags(\SplPriorityQueue::EXTR_BOTH);
        $values = $queue->toArray();

        $expect = [
            [
                'data'     => 'foo',
                'priority' => [
                    10,
                    PHP_INT_MAX,
                ],
            ],
        ];

        self::assertEquals($expect, $values);
    }

    public function testThatSerializeYieldsExpectedValues(): void
    {
        $queue = new SplPriorityQueue();
        $queue->insert('foo', 10);
        $values = $queue->__serialize();

        self::assertSame('foo', $values[0]['data']);
        self::assertSame(10, $values[0]['priority'][0]);
    }

    public function testThatSerializationPreservesInsertionOrder(): void
    {
        $queue = new SplPriorityQueue();
        $queue->insert('a', 100);
        $queue->insert('b', 200);

        $serialized = serialize($queue);
        $clone      = unserialize($serialized);
        self::assertInstanceOf(SplPriorityQueue::class, $clone);

        self::assertSame(iterator_to_array($queue), iterator_to_array($clone));
    }
}
