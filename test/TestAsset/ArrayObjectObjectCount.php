<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\TestAsset;

use Countable;

class ArrayObjectObjectCount implements Countable
{
    /** @return int */
    public function count()
    {
        return 42;
    }
}
