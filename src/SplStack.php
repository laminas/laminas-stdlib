<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib;

use Serializable;

use function serialize;
use function unserialize;

/**
 * Serializable version of SplStack
 *
 * @template TValue
 * @template-extends \SplStack<TValue>
 */
class SplStack extends \SplStack implements Serializable
{
    /**
     * Serialize to an array representing the stack
     *
     * @return array
     * @psalm-return array<int, TValue>
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
     *
     * @return string
     */
    public function serialize()
    {
        return serialize($this->toArray());
    }

    /**
     * Unserialize
     *
     * @param  string $data
     * @return void
     */
    public function unserialize($data)
    {
        /** @psalm-var TValue[] $unserialized */
        $unserialized = unserialize($data);
        foreach ($unserialized as $item) {
            $this->unshift($item);
        }
    }
}
