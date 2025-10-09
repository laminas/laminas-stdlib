<?php

declare(strict_types=1);

namespace LaminasBench\Stdlib;

use Laminas\Stdlib\FastPriorityQueue;
use Laminas\Stdlib\PriorityQueue;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;

use function rand;

#[Revs(1000)]
#[Iterations(10)]
#[Warmup(2)]
final class RemovePriorityQueueBench
{
    private FastPriorityQueue $fastPriorityQueue;
    private PriorityQueue $priorityQueue;

    public function __construct()
    {
        $this->fastPriorityQueue = new FastPriorityQueue();
        $this->priorityQueue     = new PriorityQueue();

        for ($i = 0; $i < 1000; $i += 1) {
            $priority = rand(1, 100);
            $this->fastPriorityQueue->insert('foo', $priority);
            $this->priorityQueue->insert('foo', $priority);
        }
    }

    public function benchRemovePriorityQueue(): void
    {
        $this->priorityQueue->remove('foo');
    }

    public function benchRemoveFastPriorityQueue(): void
    {
        $this->fastPriorityQueue->remove('foo');
    }
}
