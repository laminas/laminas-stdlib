<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\TestAsset\ArrayObjectObjectCount;

use Countable;

class LegacyImplementation implements Countable
{
    /** @return int */
    public function count()
    {
        return 42;
    }
}
