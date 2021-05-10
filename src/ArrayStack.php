<?php

namespace Laminas\Stdlib;

use ArrayIterator;
use ArrayObject as PhpArrayObject;

/**
 * ArrayObject that acts as a stack with regards to iteration
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
     */
    public function getIterator()
    {
        $array = $this->getArrayCopy();
        return new ArrayIterator(array_reverse($array));
    }
}
