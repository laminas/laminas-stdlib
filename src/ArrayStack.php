<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib;

use ArrayIterator;
use ArrayObject as PhpArrayObject;

use function array_reverse;

/**
 * ArrayObject that acts as a stack with regards to iteration
 *
 * @template TKey of array-key
 * @template TValue
 * @template-extends PhpArrayObject<TKey, TValue>
 */
class ArrayStack extends PhpArrayObject
{
    /**
     * Retrieve iterator
     *
     * Retrieve an array copy of the object, reverse its order, and return an
     * ArrayIterator with that reversed array.
     *
     * @return ArrayIterator
     *
     * @psalm-return ArrayIterator<TKey, TValue>
     */
    public function getIterator()
    {
        $array = $this->getArrayCopy();
        $reversed = array_reverse($array);
        return new ArrayIterator($reversed);
    }
}
