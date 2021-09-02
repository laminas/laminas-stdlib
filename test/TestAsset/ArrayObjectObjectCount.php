<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\TestAsset;

use Countable;
use ReturnTypeWillChange;

class ArrayObjectObjectCount implements Countable
{
    /**
     * @return int
     */
    #[ReturnTypeWillChange]
    public function count()
    {
        return 42;
    }
}
