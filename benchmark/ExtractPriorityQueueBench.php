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
final class ExtractPriorityQueueBench
{
    private SplPriorityQueue $splPriorityQueue;
    private FastPriorityQueue $fastPriorityQueue;
    private PriorityQueue $priorityQueue;

    public function __construct()
    {
        $this->splPriorityQueue  = new SplPriorityQueue();
        $this->fastPriorityQueue = new FastPriorityQueue();
        $this->priorityQueue     = new PriorityQueue();

        for ($i = 0; $i < 5000; $i += 1) {
            $priority = rand(1, 100);
            $this->splPriorityQueue->insert('foo', $priority);
            $this->fastPriorityQueue->insert('foo', $priority);
            $this->priorityQueue->insert('foo', $priority);
        }
    }

    public function benchExtractSplPriorityQueue(): void
    {
        $this->splPriorityQueue->extract();
    }

    public function benchExtractPriorityQueue(): void
    {
        $this->priorityQueue->extract();
    }

    public function benchExtractFastPriorityQueue(): void
    {
        $this->fastPriorityQueue->extract();
    }
}
