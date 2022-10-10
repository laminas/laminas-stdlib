<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\TestAsset;

use Iterator;
use ReturnTypeWillChange;

use function current;
use function key;
use function next;
use function reset;

class IteratorWithToArrayMethod implements Iterator
{
    public function __construct(private array $elements)
    {
    }

    /** @return void */
    #[ReturnTypeWillChange]
    public function rewind()
    {
        reset($this->elements);
    }

    /** @return mixed */
    #[ReturnTypeWillChange]
    public function current()
    {
        return current($this->elements);
    }

    /** @return int|string */
    #[ReturnTypeWillChange]
    public function key()
    {
        return key($this->elements);
    }

    /** @return mixed */
    #[ReturnTypeWillChange]
    public function next()
    {
        return next($this->elements);
    }

    /** @return bool */
    #[ReturnTypeWillChange]
    public function valid()
    {
        $key = key($this->elements);
        return $key !== null && $key !== false;
    }

    public function toArray(): array
    {
        return [
            'data from to array' => 'not good',
        ];
    }
}
