<?php

declare(strict_types=1);

namespace LaminasBench\Stdlib;

use Laminas\Stdlib\FastPriorityQueue;
use Laminas\Stdlib\PriorityQueue;
use Laminas\Stdlib\SplPriorityQueue;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;

use function rand;

#[Revs(1000)]
#[Iterations(10)]
#[Warmup(2)]
final class InsertPriorityQueueBench
{
    private SplPriorityQueue $splPriorityQueue;
    private FastPriorityQueue $fastPriorityQueue;
    private PriorityQueue $priorityQueue;

    public function __construct()
    {
        $this->splPriorityQueue  = new SplPriorityQueue();
        $this->fastPriorityQueue = new FastPriorityQueue();
        $this->priorityQueue     = new PriorityQueue();
    }

    public function benchInsertSplPriorityQueue(): void
    {
        $this->splPriorityQueue->insert('foo', rand(1, 100));
    }

    public function benchInsertPriorityQueue(): void
    {
        $this->priorityQueue->insert('foo', rand(1, 100));
    }

    public function benchInsertFastPriorityQueue(): void
    {
        $this->fastPriorityQueue->insert('foo', rand(1, 100));
    }
}
