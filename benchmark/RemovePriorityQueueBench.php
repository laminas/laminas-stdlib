<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasBench\Stdlib;

use Laminas\Stdlib\FastPriorityQueue;
use Laminas\Stdlib\PriorityQueue;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\Revs;
use PhpBench\Benchmark\Metadata\Annotations\Warmup;

/**
 * @Revs(1000)
 * @Iterations(10)
 * @Warmup(2)
 */
class RemovePriorityQueueBench
{
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

    public function benchRemovePriorityQueue()
    {
        $this->priorityQueue->remove('foo');
    }

    public function benchRemoveFastPriorityQueue()
    {
        $this->fastPriorityQueue->remove('foo');
    }
}
