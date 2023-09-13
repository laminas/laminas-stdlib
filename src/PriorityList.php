<?php

declare(strict_types=1);

namespace Laminas\Stdlib;

use Countable;
use Exception;
use Iterator;
use Traversable;

use function array_map;
use function current;
use function key;
use function next;
use function reset;
use function uasort;

/**
 * @template TKey of string
 * @template TValue of mixed
 * @template-implements Iterator<TKey, TValue>
 */
class PriorityList implements Iterator, Countable
{
    public const EXTR_DATA     = 0x00000001;
    public const EXTR_PRIORITY = 0x00000002;
    public const EXTR_BOTH     = 0x00000003;

    /**
     * Internal list of all items.
     *
     * @var array<TKey, array{data: TValue, priority: int, serial: positive-int|0}>
     */
    protected array $items = [];

    /**
     * Serial assigned to items to preserve LIFO.
     *
     * @var positive-int|0
     */
    protected int $serial = 0;

    // phpcs:disable WebimpressCodingStandard.NamingConventions.ValidVariableName.NotCamelCapsProperty

    /**
     * Serial order mode
     */
    protected int $isLIFO = 1;

    // phpcs:enable

    /**
     * Internal counter to avoid usage of count().
     */
    protected int $count = 0;

    /**
     * Whether the list was already sorted.
     */
    protected bool $sorted = false;

    /**
     * Insert a new item.
     *
     * @param TKey   $name
     * @param TValue $value
     * @return true
     */
    public function insert($name, mixed $value, int $priority = 0): bool
    {
        if (! isset($this->items[$name])) {
            $this->count++;
        }

        $this->sorted = false;

        $this->items[$name] = [
            'data'     => $value,
            'priority' => $priority,
            'serial'   => $this->serial++,
        ];

        return true;
    }

    /**
     * @param TKey $name
     * @throws Exception
     */
    public function setPriority($name, int $priority): void
    {
        if (! isset($this->items[$name])) {
            throw new Exception("item $name not found");
        }

        $this->items[$name]['priority'] = $priority;
        $this->sorted                   = false;
    }

    /**
     * Remove a item.
     *
     * @param  TKey $name
     */
    public function remove($name): void
    {
        if (isset($this->items[$name])) {
            $this->count--;
        }

        unset($this->items[$name]);
    }

    /**
     * Remove all items.
     */
    public function clear(): void
    {
        $this->items  = [];
        $this->serial = 0;
        $this->count  = 0;
        $this->sorted = false;
    }

    /**
     * Get a item.
     *
     * @param  TKey $name
     * @return TValue|null
     */
    public function get($name): mixed
    {
        if (! isset($this->items[$name])) {
            return null;
        }

        return $this->items[$name]['data'];
    }

    /**
     * Sort all items.
     */
    protected function sort(): void
    {
        if (! $this->sorted) {
            uasort($this->items, [$this, 'compare']);
            $this->sorted = true;
        }
    }

    /**
     * Compare the priority of two items.
     *
     * @param  array $item1,
     */
    protected function compare(array $item1, array $item2): int
    {
        return $item1['priority'] === $item2['priority']
            ? ($item1['serial'] > $item2['serial'] ? -1 : 1) * $this->isLIFO
            : ($item1['priority'] > $item2['priority'] ? -1 : 1);
    }

    /**
     * Get/Set serial order mode
     */
    public function isLIFO(?bool $flag = null): bool
    {
        if ($flag !== null) {
            $isLifo = $flag === true ? 1 : -1;

            if ($isLifo !== $this->isLIFO) {
                $this->isLIFO = $isLifo;
                $this->sorted = false;
            }
        }

        return 1 === $this->isLIFO;
    }

    /**
     * {@inheritDoc}
     */
    public function rewind(): void
    {
        $this->sort();
        reset($this->items);
    }

    /**
     * @return TValue|null
     */
    public function current(): mixed
    {
        $this->sorted || $this->sort();
        $node = current($this->items);

        return $node ? $node['data'] : null;
    }

    /**
     * @return TKey|null
     */
    public function key(): int|string|null
    {
        $this->sorted || $this->sort();
        return key($this->items);
    }

    public function next(): void
    {
        next($this->items);
    }

    public function valid(): bool
    {
        return current($this->items) !== false;
    }

    /**
     * @return Traversable<TKey, TValue>
     */
    public function getIterator(): Traversable
    {
        return clone $this;
    }

    public function count(): int
    {
        return $this->count;
    }

    /**
     * Return list as array
     *
     * @param self::EXTR_* $flag
     * @return array
     */
    public function toArray(int $flag = self::EXTR_DATA): array
    {
        $this->sort();

        if ($flag === self::EXTR_BOTH) {
            return $this->items;
        }

        return array_map(
            static fn($item) => $flag === self::EXTR_PRIORITY ? $item['priority'] : $item['data'],
            $this->items
        );
    }
}
