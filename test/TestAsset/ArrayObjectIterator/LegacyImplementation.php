<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\TestAsset\ArrayObjectIterator;

use Iterator;

use function current;
use function is_array;
use function key;
use function next;
use function reset;

class LegacyImplementation implements Iterator
{
    /** @var array */
    private $var = [];

    /** @param array $array */
    public function __construct($array)
    {
        if (is_array($array)) {
            $this->var = $array;
        }
    }

    /** @return void */
    public function rewind()
    {
        reset($this->var);
    }

    /** @return mixed */
    public function current()
    {
        return current($this->var);
    }

    /** @return int|string */
    public function key()
    {
        return key($this->var);
    }

    /** @return mixed */
    public function next()
    {
        return next($this->var);
    }

    /** @return bool */
    public function valid()
    {
        $key = key($this->var);
        return $key !== null && $key !== false;
    }
}
