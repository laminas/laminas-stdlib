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

class InsertPriorityQueue extends AthleticEvent
{
    public function classSetUp()
    {
        $this->splPriorityQueue  = new SplPriorityQueue();
        $this->fastPriorityQueue = new FastPriorityQueue();
        $this->priorityQueue     = new PriorityQueue();
    }

    /**
     * @iterations 5000
     */
    public function insertSplPriorityQueue()
    {
        $this->splPriorityQueue->insert('foo', rand(1, 100));
    }

    /**
     * @iterations 5000
     */
    public function insertPriorityQueue()
    {
        $this->priorityQueue->insert('foo', rand(1, 100));
    }

    /**
     * @iterations 5000
     */
    public function insertFastPriorityQueue()
    {
        $this->fastPriorityQueue->insert('foo', rand(1, 100));
    }
}
