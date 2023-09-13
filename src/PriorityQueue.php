<?php

declare(strict_types=1);

namespace Laminas\Stdlib;

use Countable;
use IteratorAggregate;
use Serializable;
use SplPriorityQueue as PhpSplPriorityQueue;
use Traversable;
use UnexpectedValueException;

use function array_map;
use function count;
use function is_array;
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
 *
 * @template TValue
 * @implements IteratorAggregate<array-key, TValue>
 */
class PriorityQueue implements Countable, IteratorAggregate, Serializable
{
    public const EXTR_DATA     = 0x00000001;
    public const EXTR_PRIORITY = 0x00000002;
    public const EXTR_BOTH     = 0x00000003;

    /**
     * Inner queue class to use for iteration
     *
     * @var class-string<PhpSplPriorityQueue>
     */
    protected string $queueClass = SplPriorityQueue::class;

    /**
     * Actual items aggregated in the priority queue. Each item is an array
     * with keys "data" and "priority".
     *
     * @var list<array{data: TValue, priority: int}>
     */
    protected array $items = [];

    /**
     * Inner queue object
     *
     * @var PhpSplPriorityQueue<int, TValue>|null
     */
    protected ?PhpSplPriorityQueue $queue = null;

    /**
     * Insert an item into the queue
     *
     * Priority defaults to 1 (low priority) if none provided.
     *
     * @param TValue $data
     * @return true
     */
    public function insert(mixed $data, int $priority = 1): bool
    {
        $this->items[] = [
            'data'     => $data,
            'priority' => $priority,
        ];
        $this->getQueue()->insert($data, $priority);

        return true;
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
     * @return bool False if the item was not found, true otherwise.
     */
    public function remove(mixed $datum)
    {
        $found = false;
        $key   = null;
        foreach ($this->items as $key => $item) {
            if ($item['data'] === $datum) {
                $found = true;
                break;
            }
        }
        if ($found && $key !== null) {
            unset($this->items[$key]);
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

    public function isEmpty(): bool
    {
        return 0 === $this->count();
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Peek at the top node in the queue, based on priority.
     *
     * @return TValue
     */
    public function top(): mixed
    {
        $queue = clone $this->getQueue();

        return $queue->top();
    }

    /**
     * Extract a node from the inner queue and sift up
     *
     * @return TValue
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
     * @return PhpSplPriorityQueue<int, TValue>
     */
    public function getIterator(): Traversable
    {
        $queue = $this->getQueue();
        return clone $queue;
    }

    public function serialize(): string
    {
        return serialize($this->__serialize());
    }

    /**
     * Magic method used for serializing of an instance.
     *
     * @return list<array{data: TValue, priority: int}>
     */
    public function __serialize(): array
    {
        return $this->items;
    }

    /**
     * Unserialize a string into a PriorityQueue object
     *
     * Serialization format is compatible with {@link SplPriorityQueue}
     */
    public function unserialize(string $data): void
    {
        $toUnserialize = unserialize($data);
        if (! is_array($toUnserialize)) {
            throw new UnexpectedValueException(sprintf(
                'Cannot deserialize %s instance; corrupt serialization data',
                self::class
            ));
        }

        /** @psalm-var list<array{data: TValue, priority: int}> $toUnserialize */

        $this->__unserialize($toUnserialize);
    }

   /**
    * Magic method used to rebuild an instance.
    *
    * @param list<array{data: TValue, priority: int}> $data Data array
    */
    public function __unserialize(array $data): void
    {
        foreach ($data as $item) {
            $this->insert($item['data'], $item['priority']);
        }
    }

    /**
     * Serialize to an array
     * By default, returns only the item data, and in the order registered (not
     * sorted). You may provide one of the EXTR_* flags as an argument, allowing
     * the ability to return priorities or both data and priority.
     *
     * @param self::EXTR_* $flag
     * @return array<array-key, mixed>
     * @psalm-return ($flag is self::EXTR_BOTH
     *                      ? list<array{data: TValue, priority: int}>
     *                      : $flag is self::EXTR_PRIORITY
     *                          ? list<int>
     *                          : list<TValue>
     *               )
     */
    public function toArray(int $flag = self::EXTR_DATA): array
    {
        return match ($flag) {
            self::EXTR_BOTH => $this->items,
            self::EXTR_PRIORITY => array_map(static fn($item): int => $item['priority'], $this->items),
            default => array_map(static fn($item): mixed => $item['data'], $this->items),
        };
    }

    /**
     * Specify the internal queue class
     *
     * Please see {@link getIterator()} for details on the necessity of an
     * internal queue class. The class provided should extend SplPriorityQueue.
     *
     * @param  class-string<PhpSplPriorityQueue> $class
     * @return $this
     */
    public function setInternalQueueClass(string $class): self
    {
        $this->queueClass = $class;
        return $this;
    }

    /**
     * Does the queue contain the given datum?
     *
     * @param  TValue $datum
     */
    public function contains(mixed $datum): bool
    {
        foreach ($this->items as $item) {
            if ($item['data'] === $datum) {
                return true;
            }
        }
        return false;
    }

    /**
     * Does the queue have an item with the given priority?
     */
    public function hasPriority(int $priority): bool
    {
        foreach ($this->items as $item) {
            if ($item['priority'] === $priority) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get the inner priority queue instance
     *
     * @return PhpSplPriorityQueue<int, TValue>
     * @psalm-assert !null $this->queue
     * @throws Exception\DomainException
     */
    protected function getQueue(): PhpSplPriorityQueue
    {
        if (null === $this->queue) {
            /** @psalm-suppress UnsafeInstantiation */
            $queue = new $this->queueClass();
            /** @psalm-var PhpSplPriorityQueue<int, TValue> $queue */
            $this->queue = $queue;
            /** @psalm-suppress DocblockTypeContradiction */
            if (! $this->queue instanceof PhpSplPriorityQueue) {
                throw new Exception\DomainException(sprintf(
                    'PriorityQueue expects an internal queue of type SplPriorityQueue; received "%s"',
                    $this->queue::class
                ));
            }
        }

        return $this->queue;
    }

    public function __clone()
    {
        $this->queue = clone $this->getQueue();
    }
}
