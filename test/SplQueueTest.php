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
    /** @var SplQueue */
    protected $queue;

    protected function setUp(): void
    {
        $this->queue = new SplQueue();
        $this->queue->push('foo');
        $this->queue->push('bar');
        $this->queue->push('baz');
    }

    public function testSerializationAndDeserializationShouldMaintainState(): void
    {
        $s            = serialize($this->queue);
        $unserialized = unserialize($s);
        $count        = count($this->queue);
        self::assertSame($count, count($unserialized));

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
