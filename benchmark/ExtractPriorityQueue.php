<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasBench\Stdlib;

use Athletic\AthleticEvent;
use Laminas\Stdlib\FastPriorityQueue;
use Laminas\Stdlib\PriorityQueue;
use Laminas\Stdlib\SplPriorityQueue;

class ExtractPriorityQueue extends AthleticEvent
{
    public function classSetUp()
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

    /**
     * @iterations 5000
     */
    public function extractSplPriorityQueue()
    {
        $this->splPriorityQueue->extract();
    }

    /**
     * @iterations 5000
     */
    public function extractPriorityQueue()
    {
        $this->priorityQueue->extract();
    }

    /**
     * @iterations 5000
     */
    public function extractFastPriorityQueue()
    {
        $this->fastPriorityQueue->extract();
    }
}
