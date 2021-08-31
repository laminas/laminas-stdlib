<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\TestAsset\ArrayObjectObjectCount;

use Countable;

class PHP81Implementation implements Countable
{
    public function count(): int
    {
        return 42;
    }
}
