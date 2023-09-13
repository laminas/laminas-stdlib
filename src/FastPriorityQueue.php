<?php

declare(strict_types=1);

namespace Laminas\Stdlib;

use Countable;
use Iterator;
use Serializable;
use SplPriorityQueue as PhpSplPriorityQueue;
use UnexpectedValueException;

use function assert;
use function current;
use function in_array;
use function is_array;
use function key;
use function max;
use function next;
use function reset;
use function serialize;
use function sprintf;
use function unserialize;

/**
 * This is an efficient implementation of an integer priority queue in PHP
 *
 * This class acts like a queue with insert() and extract(), removing the
 * elements from the queue and it also acts like an Iterator without removing
 * the elements. This behaviour can be used in mixed scenarios with high
 * performance boost.
 *
 * @template TValue of mixed
 * @template-implements Iterator<int, TValue>
 */
class FastPriorityQueue implements Iterator, Countable, Serializable
{
    public const EXTR_DATA     = PhpSplPriorityQueue::EXTR_DATA;
    public const EXTR_PRIORITY = PhpSplPriorityQueue::EXTR_PRIORITY;
    public const EXTR_BOTH     = PhpSplPriorityQueue::EXTR_BOTH;

    /** @var self::EXTR_* */
    protected int $extractFlag = self::EXTR_DATA;

    /**
     * Elements of the queue, divided by priorities
     *
     * @var array<int, list<TValue>>
     */
    protected array $values = [];

    /**
     * Array of priorities
     *
     * @var array<int, int>
     */
    protected array $priorities = [];

    /**
     * Array of priorities used for the iteration
     *
     * @var array
     */
    protected array $subPriorities = [];

    /**
     * Max priority
     */
    protected ?int $maxPriority = null;

    /**
     * Total number of elements in the queue
     */
    protected int $count = 0;

    /**
     * Index of the current element in the queue
     */
    protected int $index = 0;

    /**
     * Sub index of the current element in the same priority level
     */
    protected int $subIndex = 0;

    /** @return list<array{data: TValue, priority: int}> */
    public function __serialize(): array
    {
        $clone = clone $this;
        $clone->setExtractFlags(self::EXTR_BOTH);

        $data = [];
        /**
         * Forcing the type - we have explicitly set EXTR_BOTH
         *
         * @psalm-var array{data: TValue, priority: int} $item
         */
        foreach ($clone as $item) {
            $data[] = $item;
        }

        return $data;
    }

    public function __unserialize(array $data): void
    {
        foreach ($data as $item) {
            $this->insert($item['data'], $item['priority']);
        }
    }

    /**
     * Insert an element in the queue with a specified priority
     *
     * @param TValue $value
     * @return true
     */
    public function insert(mixed $value, int $priority): bool
    {
        $this->values[$priority][] = $value;
        if (! isset($this->priorities[$priority])) {
            $this->priorities[$priority] = $priority;
            $this->maxPriority           = $this->maxPriority === null ? $priority : max($priority, $this->maxPriority);
        }
        ++$this->count;

        return true;
    }

    /**
     * Extract an element in the queue according to the priority and the
     * order of insertion
     *
     * @return TValue|int|array{data: TValue, priority: int}|false
     */
    public function extract(): mixed
    {
        if (! $this->valid()) {
            return false;
        }
        $value = $this->current();
        $this->nextAndRemove();
        return $value;
    }

    /**
     * Remove an item from the queue
     *
     * This is different than {@link extract()}; its purpose is to dequeue an
     * item.
     *
     * Note: this removes the first item matching the provided item found. If
     * the same item has been added multiple times, it will not remove other
     * instances.
     *
     * @return bool False if the item was not found, true otherwise.
     */
    public function remove(mixed $datum): bool
    {
        $currentIndex    = $this->index;
        $currentSubIndex = $this->subIndex;
        $currentPriority = $this->maxPriority;

        $this->rewind();
        while ($this->valid()) {
            if (current($this->values[$this->maxPriority]) === $datum) {
                $index = key($this->values[$this->maxPriority]);
                unset($this->values[$this->maxPriority][$index]);

                // The `next()` method advances the internal array pointer, so we need to use the `reset()` function,
                // otherwise we would lose all elements before the place the pointer points.
                reset($this->values[$this->maxPriority]);

                $this->index    = $currentIndex;
                $this->subIndex = $currentSubIndex;

                // If the array is empty we need to destroy the unnecessary priority,
                // otherwise we would end up with an incorrect value of `$this->count`
                // {@see \Laminas\Stdlib\FastPriorityQueue::nextAndRemove()}.
                if (empty($this->values[$this->maxPriority])) {
                    unset($this->values[$this->maxPriority]);
                    unset($this->priorities[$this->maxPriority]);
                    if ($this->maxPriority === $currentPriority) {
                        $this->subIndex = 0;
                    }
                }

                $this->maxPriority = empty($this->priorities) ? null : max($this->priorities);
                --$this->count;
                return true;
            }
            $this->next();
        }
        return false;
    }

    /**
     * Get the total number of elements in the queue
     */
    public function count(): int
    {
        return $this->count;
    }

    /**
     * Get the current element in the queue
     *
     * @return TValue|int|array{data: TValue, priority: int}|null
     */
    public function current(): mixed
    {
        if ($this->isEmpty()) {
            return null;
        }

        assert(isset($this->values[$this->maxPriority]));

        return match ($this->extractFlag) {
            self::EXTR_DATA => current($this->values[$this->maxPriority]),
            self::EXTR_PRIORITY => $this->maxPriority,
            self::EXTR_BOTH => [
                'data'     => current($this->values[$this->maxPriority]),
                'priority' => $this->maxPriority,
            ],
        };
    }

    /**
     * Get the index of the current element in the queue
     */
    public function key(): int
    {
        return $this->index;
    }

    /**
     * Set the iterator pointer to the next element in the queue
     * removing the previous element
     */
    protected function nextAndRemove(): void
    {
        $key = key($this->values[$this->maxPriority]);

        if (false === next($this->values[$this->maxPriority])) {
            unset($this->priorities[$this->maxPriority]);
            unset($this->values[$this->maxPriority]);
            $this->maxPriority = empty($this->priorities) ? null : max($this->priorities);
            $this->subIndex    = -1;
        } else {
            unset($this->values[$this->maxPriority][$key]);
        }
        ++$this->index;
        ++$this->subIndex;
        --$this->count;
    }

    /**
     * Set the iterator pointer to the next element in the queue
     * without removing the previous element
     */
    public function next(): void
    {
        if (false === next($this->values[$this->maxPriority])) {
            unset($this->subPriorities[$this->maxPriority]);
            reset($this->values[$this->maxPriority]);
            $this->maxPriority = empty($this->subPriorities) ? null : max($this->subPriorities);
            $this->subIndex    = -1;
        }
        ++$this->index;
        ++$this->subIndex;
    }

    /**
     * Check if the current iterator is valid
     */
    public function valid(): bool
    {
        return isset($this->values[$this->maxPriority]);
    }

    /**
     * Rewind the current iterator
     */
    public function rewind(): void
    {
        $this->subPriorities = $this->priorities;
        $this->maxPriority   = empty($this->priorities) ? 0 : max($this->priorities);
        $this->index         = 0;
        $this->subIndex      = 0;
    }

    /**
     * Serialize to an array
     *
     * Array will be priority => data pairs
     *
     * @return list<TValue|int|array{data: TValue, priority: int}>
     */
    public function toArray(): array
    {
        $array = [];
        foreach (clone $this as $item) {
            $array[] = $item;
        }
        return $array;
    }

    public function serialize(): string
    {
        return serialize($this->__serialize());
    }

    public function unserialize(string $data): void
    {
        $toUnserialize = unserialize($data);
        if (! is_array($toUnserialize)) {
            throw new UnexpectedValueException(sprintf(
                'Cannot deserialize %s instance; corrupt serialization data',
                self::class
            ));
        }

        $this->__unserialize($toUnserialize);
    }

    /**
     * Set the extract flag
     *
     * @param self::EXTR_* $flag
     */
    public function setExtractFlags(int $flag): void
    {
        $this->extractFlag = match ($flag) {
            self::EXTR_DATA, self::EXTR_PRIORITY, self::EXTR_BOTH => $flag,
            default => throw new Exception\InvalidArgumentException("The extract flag specified is not valid"),
        };
    }

    /**
     * Check if the queue is empty
     */
    public function isEmpty(): bool
    {
        return empty($this->values);
    }

    /**
     * Does the queue contain the given datum?
     */
    public function contains(mixed $datum): bool
    {
        foreach ($this->values as $values) {
            if (in_array($datum, $values)) {
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
        return isset($this->values[$priority]);
    }
}
