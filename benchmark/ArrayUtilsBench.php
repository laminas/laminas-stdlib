<?php

declare(strict_types=1);

namespace LaminasBench\Stdlib;

use Laminas\Stdlib\ArrayUtils;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;

#[Revs(1000)]
#[Iterations(10)]
#[Warmup(2)]
final class ArrayUtilsBench
{
    public function benchHasStringKeys(): void
    {
        ArrayUtils::hasStringKeys([
            'key' => 'value',
        ]);
    }

    public function benchHasIntegerKeys(): void
    {
        ArrayUtils::hasIntegerKeys([
            1 => 'value',
        ]);
    }

    public function benchHasNumericKeys(): void
    {
        ArrayUtils::hasNumericKeys([
            '1' => 'value',
        ]);
    }
}
