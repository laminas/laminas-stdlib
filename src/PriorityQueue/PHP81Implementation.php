<?php

declare(strict_types=1);

namespace Laminas\Stdlib\PriorityQueue;

use Countable;
use IteratorAggregate;
use Laminas\Stdlib\Exception;
use Laminas\Stdlib\SplPriorityQueue;
use Serializable;
use Traversable;
use UnexpectedValueException;

use function array_key_exists;
use function array_map;
use function count;
use function get_class;
use function gettype;
use function is_array;
use function is_object;
use function serialize;
use function sprintf;
use function unserialize;

/**
 * Re-usable, serializable priority queue implementation
 *
 * SplPriorityQueue acts as a heap; on iteration, each item is removed from the
 * queue. If you wish to re-use such a queue, you need to clone it first. This
 * makes for some interesting issues if you wish to delete items from the queue,
 * or, as already stated, iterate over it multiple times.
 *
 * This class aggregates items for the queue itself, but also composes an
 * "inner" iterator in the form of an SplPriorityQueue object for performing
 * the actual iteration.
 */
class PHP81Implementation implements Countable, IteratorAggregate, Serializable
{
    public const EXTR_DATA     = 0x00000001;
    public const EXTR_PRIORITY = 0x00000002;
    public const EXTR_BOTH     = 0x00000003;

    /**
     * Inner queue class to use for iteration
     *
     * @var string
     */
    protected $queueClass = SplPriorityQueue::class;

    /**
     * Actual items aggregated in the priority queue. Each item is an array
     * with keys "data" and "priority".
     *
     * @var array
     * @psalm-var array<array-key, array{
     *     data: mixed,
     *     priority: int
     * }>
     */
    protected $items = [];

    /**
     * Inner queue object
     *
     * @var null|SplPriorityQueue
     */
    protected $queue;

    /**
     * Insert an item into the queue
     *
     * Priority defaults to 1 (low priority) if none provided.
     *
     * @param  mixed $data
     * @param  int $priority
     * @return $this
     */
    public function insert($data, $priority = 1)
    {
        $priority      = (int) $priority;
        $this->items[] = [
            'data'     => $data,
            'priority' => $priority,
        ];
        $this->getQueue()->insert($data, $priority);
        return $this;
    }

    /**
     * Remove an item from the queue
     *
     * This is different than {@link extract()}; its purpose is to dequeue an
     * item.
     *
     * This operation is potentially expensive, as it requires
     * re-initialization and re-population of the inner queue.
     *
     * Note: this removes the first item matching the provided item found. If
     * the same item has been added multiple times, it will not remove other
     * instances.
     *
     * @param  mixed $datum
     * @return bool False if the item was not found, true otherwise.
     */
    public function remove($datum)
    {
        $keyToRemove = null;
        foreach ($this->items as $key => $item) {
            if ($item['data'] === $datum) {
                $keyToRemove = $key;
                break;
            }
        }

        if ($keyToRemove !== null) {
            unset($this->items[$keyToRemove]);
            $this->queue = null;

            if (! $this->isEmpty()) {
                $queue = $this->getQueue();
                foreach ($this->items as $item) {
                    $queue->insert($item['data'], $item['priority']);
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Is the queue empty?
     *
     * @return bool
     */
    public function isEmpty()
    {
        return 0 === $this->count();
    }

    /**
     * How many items are in the queue?
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Peek at the top node in the queue, based on priority.
     *
     * @return mixed
     */
    public function top()
    {
        return $this->getIterator()->top();
    }

    /**
     * Extract a node from the inner queue and sift up
     *
     * @return mixed
     */
    public function extract()
    {
        $value = $this->getQueue()->extract();

        $keyToRemove     = null;
        $highestPriority = null;
        foreach ($this->items as $key => $item) {
            if ($item['data'] !== $value) {
                continue;
            }

            if (null === $highestPriority) {
                $highestPriority = $item['priority'];
                $keyToRemove     = $key;
                continue;
            }

            if ($highestPriority >= $item['priority']) {
                continue;
            }

            $highestPriority = $item['priority'];
            $keyToRemove     = $key;
        }

        if ($keyToRemove !== null) {
            unset($this->items[$keyToRemove]);
        }

        return $value;
    }

    /**
     * Retrieve the inner iterator
     *
     * SplPriorityQueue acts as a heap, which typically implies that as items
     * are iterated, they are also removed. This does not work for situations
     * where the queue may be iterated multiple times. As such, this class
     * aggregates the values, and also injects an SplPriorityQueue. This method
     * retrieves the inner queue object, and clones it for purposes of
     * iteration.
     *
     * @return SplPriorityQueue
     */
    public function getIterator(): Traversable
    {
        $queue = $this->getQueue();
        return clone $queue;
    }

    /**
     * Serialize the data structure
     *
     * @return string
     */
    public function serialize()
    {
        return serialize($this->items);
    }

    /**
     * Magic method used for serializing of an instance.
     *
     * @return array
     */
    public function __serialize()
    {
        return $this->items;
    }

    /**
     * Unserialize a string into a PriorityQueue object
     *
     * Serialization format is compatible with {@link Laminas\Stdlib\SplPriorityQueue}
     *
     * @param  string $data
     * @return void
     */
    public function unserialize($data)
    {
        $toUnserialize = unserialize($data);
        if (! is_array($toUnserialize)) {
            throw new UnexpectedValueException(sprintf(
                'Unable to deserialize to Laminas\Stdlib\PriorityQueue; expected array, received %s',
                is_object($toUnserialize) ? get_class($toUnserialize) : gettype($toUnserialize)
            ));
        }

        $this->__unserialize($toUnserialize);
    }

   /**
    * Magic method used to rebuild an instance.
    *
    * @param array $data Data array.
    * @return void
    */
    public function __unserialize($data)
    {
        foreach ($data as $item) {
            if (! is_array($item) || ! array_key_exists('data', $item)) {
                throw new UnexpectedValueException(
                    'Unable to deserialize to Laminas\Stdlib\PriorityQueue; corrupt item'
                );
            }

            $priority = 1;
            if (array_key_exists('priority', $item)) {
                $priority = (int) $item['priority'];
            }

            $this->insert($item['data'], $priority);
        }
    }

    /**
     * Serialize to an array
     *
     * By default, returns only the item data, and in the order registered (not
     * sorted). You may provide one of the EXTR_* flags as an argument, allowing
     * the ability to return priorities or both data and priority.
     *
     * @param  int $flag
     * @return array
     */
    public function toArray($flag = self::EXTR_DATA)
    {
        switch ($flag) {
            case self::EXTR_BOTH:
                return $this->items;

            case self::EXTR_PRIORITY:
                return array_map(function (array $item): int {
                    $priority = 1;
                    if (array_key_exists('priority', $item)) {
                        $priority = (int) $item['priority'];
                    }
                    return $priority;
                }, $this->items);

            case self::EXTR_DATA:
            default:
                return array_map(function (array $item) {
                    return $item['data'] ?? null;
                }, $this->items);
        }
    }

    /**
     * Specify the internal queue class
     *
     * Please see {@link getIterator()} for details on the necessity of an
     * internal queue class. The class provided should extend SplPriorityQueue.
     *
     * @param  string $class
     * @return $this
     */
    public function setInternalQueueClass($class)
    {
        $this->queueClass = (string) $class;
        return $this;
    }

    /**
     * Does the queue contain the given datum?
     *
     * @param  mixed $datum
     * @return bool
     */
    public function contains($datum)
    {
        foreach ($this->items as $item) {
            if (! is_array($item) || ! isset($item['data'])) {
                continue;
            }

            if ($item['data'] === $datum) {
                return true;
            }
        }
        return false;
    }

    /**
     * Does the queue have an item with the given priority?
     *
     * @param  int $priority
     * @return bool
     */
    public function hasPriority($priority)
    {
        foreach ($this->items as $item) {
            if (! is_array($item) || ! isset($item['priority'])) {
                continue;
            }

            if ($item['priority'] === $priority) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get the inner priority queue instance
     *
     * @throws Exception\DomainException
     * @return SplPriorityQueue
     */
    protected function getQueue()
    {
        if (null === $this->queue) {
            $queue = new $this->queueClass();
            if (! $queue instanceof SplPriorityQueue) {
                throw new Exception\DomainException(sprintf(
                    'PriorityQueue expects an internal queue of type SplPriorityQueue; received "%s"',
                    get_class($queue)
                ));
            }

            $this->queue = $queue;
        }

        return $this->queue;
    }

    /**
     * Add support for deep cloning
     *
     * @return void
     */
    public function __clone()
    {
        if (null !== $this->queue) {
            $this->queue = clone $this->queue;
        }
    }
}
