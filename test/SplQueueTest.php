<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\SplQueue;
use PHPUnit\Framework\TestCase;

use function count;
use function iterator_to_array;
use function serialize;
use function unserialize;

class SplQueueTest extends TestCase
{
    /** @var SplQueue<int, non-empty-string> */
    protected $queue;

    protected function setUp(): void
    {
        /** @var SplQueue<int, non-empty-string> $splQueue */
        $splQueue    = new SplQueue();
        $this->queue = $splQueue;
        $this->queue->push('foo');
        $this->queue->push('bar');
        $this->queue->push('baz');
    }

    public function testSerializationAndDeserializationShouldMaintainState(): void
    {
        $s            = serialize($this->queue);
        $unserialized = unserialize($s);
        self::assertInstanceOf(SplQueue::class, $unserialized);
        self::assertSame(count($this->queue), count($unserialized));

        $expected = iterator_to_array($this->queue);
        $test     = iterator_to_array($unserialized);
        self::assertSame($expected, $test);
    }

    public function testCanRetrieveQueueAsArray(): void
    {
        $expected = ['foo', 'bar', 'baz'];
        self::assertSame($expected, $this->queue->toArray());
    }
}
