<?php

declare(strict_types=1);

namespace Laminas\Stdlib\SplQueue;

use SplQueue;

use function serialize;
use function unserialize;

/**
 * Serializable version of SplQueue
 */
class PHP81Implementation extends SplQueue
{
    /**
     * Return an array representing the queue
     *
     * @return array
     */
    public function toArray()
    {
        $array = [];
        foreach ($this as $item) {
            $array[] = $item;
        }
        return $array;
    }

    /**
     * Serialize
     */
    public function serialize(): string
    {
        return serialize($this->toArray());
    }

    /**
     * Magic method used for serializing of an instance.
     */
    public function __serialize(): array
    {
        return $this->toArray();
    }

    /**
     * Unserialize
     */
    public function unserialize(string $data): void
    {
        foreach (unserialize($data) as $item) {
            $this->push($item);
        }
    }

   /**
    * Magic method used to rebuild an instance.
    */
    public function __unserialize(array $data): void
    {
        /** @psalm-suppress MixedAssignment */
        foreach ($data as $item) {
            $this->push($item);
        }
    }
}
