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

class RemovePriorityQueue extends AthleticEvent
{
    public function classSetUp()
    {
        $this->fastPriorityQueue = new FastPriorityQueue();
        $this->priorityQueue     = new PriorityQueue();

        for ($i = 0; $i < 1000; $i += 1) {
            $priority = rand(1, 100);
            $this->fastPriorityQueue->insert('foo', $priority);
            $this->priorityQueue->insert('foo', $priority);
        }
    }

    /**
     * @iterations 1000
     */
    public function removePriorityQueue()
    {
        $this->priorityQueue->remove('foo');
    }

    /**
     * @iterations 1000
     */
    public function removeFastPriorityQueue()
    {
        $this->fastPriorityQueue->remove('foo');
    }
}
