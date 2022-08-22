<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\TestAsset;

use Iterator;
use ReturnTypeWillChange;

use function current;
use function is_array;
use function key;
use function next;
use function reset;

class ArrayObjectIterator implements Iterator
{
    private array $var = [];

    /** @param array $array */
    public function __construct($array)
    {
        if (is_array($array)) {
            $this->var = $array;
        }
    }

    /** @return void */
    #[ReturnTypeWillChange]
    public function rewind()
    {
        reset($this->var);
    }

    /** @return mixed */
    #[ReturnTypeWillChange]
    public function current()
    {
        return current($this->var);
    }

    /** @return int|string */
    #[ReturnTypeWillChange]
    public function key()
    {
        return key($this->var);
    }

    /** @return mixed */
    #[ReturnTypeWillChange]
    public function next()
    {
        return next($this->var);
    }

    /** @return bool */
    #[ReturnTypeWillChange]
    public function valid()
    {
        $key = key($this->var);
        return $key !== null && $key !== false;
    }
}
