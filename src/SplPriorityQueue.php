<?php

declare(strict_types=1);

namespace Laminas\Stdlib;

use Serializable;
use UnexpectedValueException;

use function array_key_exists;
use function count;
use function get_debug_type;
use function is_array;
use function min;
use function serialize;
use function sprintf;
use function unserialize;

use const PHP_INT_MAX;

/**
 * Serializable version of SplPriorityQueue
 *
 * Also, provides predictable heap order for datums added with the same priority
 * (i.e., they will be emitted in the same order they are enqueued).
 *
 * @template TValue
 * @template TPriority of int
 * @psalm-type InternalPriority = array{0: mixed, 1: int}
 * @extends \SplPriorityQueue<InternalPriority, TValue>
 */
class SplPriorityQueue extends \SplPriorityQueue implements Serializable
{
    /** Seed used to ensure queue order for items of the same priority */
    private int $serial = PHP_INT_MAX;

    /**
     * Insert a value with a given priority
     *
     * Utilizes {@var $serial} to ensure that values of equal priority are
     * emitted in the same order in which they are inserted.
     *
     * @param TValue $value
     * @param TPriority|InternalPriority $priority
     * @return true
     */
    public function insert(mixed $value, mixed $priority): bool
    {
        if (! is_array($priority)) {
            $priority = [$priority, $this->serial--];
        }

        parent::insert($value, $priority);

        return true;
    }

    /**
     * Serialize to an array
     *
     * Array will be priority => data pairs
     *
     * @return list<TValue>|list<InternalPriority>|list<array{data: TValue, priority: InternalPriority}>
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

    /**
     * Magic method used for serializing of an instance.
     *
     * @return list<array{data: TValue, priority: InternalPriority}>
     */
    public function __serialize(): array
    {
        $clone = clone $this;
        $clone->setExtractFlags(self::EXTR_BOTH);

        $data = [];
        /**
         * The type needs to be forced here because psalm does not know that setExtractFlags() alters the iterable value
         *
         * @psalm-var array{data: TValue, priority: InternalPriority} $item
         */
        foreach ($clone as $item) {
            $data[] = $item;
        }
        return $data;
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
     * Magic method used to rebuild an instance.
     *
     * @param array<array-key, array{data: TValue, priority: InternalPriority}> $data Data array.
     */
    public function __unserialize(array $data): void
    {
        /** @psalm-var non-empty-list<int> $serials */
        $serials = [
            PHP_INT_MAX,
        ];

        foreach ($data as $item) {
            if (! is_array($item)) {
                throw new UnexpectedValueException(sprintf(
                    'Cannot deserialize %s instance: corrupt item; expected array, received %s',
                    self::class,
                    get_debug_type($item)
                ));
            }

            if (! array_key_exists('data', $item)) {
                throw new UnexpectedValueException(sprintf(
                    'Cannot deserialize %s instance: corrupt item; missing "data" element',
                    self::class
                ));
            }

            if (
                ! array_key_exists('priority', $item)
                || ! is_array($item['priority'])
                || count($item['priority']) !== 2
            ) {
                throw new UnexpectedValueException(sprintf(
                    'Cannot deserialize %s instance: corrupt item; missing or invalid "priority" element',
                    self::class
                ));
            }

            $serials[] = $item['priority'][1];

            $this->insert($item['data'], $item['priority']);
        }

        $this->serial = min($serials) - 1;
    }
}
