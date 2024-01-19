<?php

declare(strict_types=1);

namespace Laminas\Stdlib;

use Serializable;
use UnexpectedValueException;

use function is_array;
use function serialize;
use function sprintf;
use function unserialize;

/**
 * Serializable version of SplQueue
 *
 * @template TKey of array-key
 * @template TValue
 * @extends \SplQueue<TValue>
 */
class SplQueue extends \SplQueue implements Serializable
{
    /**
     * Return an array representing the queue
     *
     * @return list<TValue>
     */
    public function toArray(): array
    {
        $array = [];
        foreach ($this as $item) {
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
     * @return list<TValue>
     */
    public function __serialize(): array
    {
        return $this->toArray();
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
    * @param array<array-key, TValue> $data Data array.
    */
    public function __unserialize(array $data): void
    {
        foreach ($data as $item) {
            $this->push($item);
        }
    }
}
