<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasBench\Stdlib;

use Laminas\Stdlib\ArrayUtils;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\Revs;
use PhpBench\Benchmark\Metadata\Annotations\Warmup;

/**
 * @Revs(1000)
 * @Iterations(10)
 * @Warmup(2)
 */
class ArrayUtilsBench
{
    public function benchHasStringKeys()
    {
        ArrayUtils::hasStringKeys([
            'key' => 'value',
        ]);
    }

    public function benchHasIntegerKeys()
    {
        ArrayUtils::hasIntegerKeys([
            1 => 'value',
        ]);
    }

    public function benchHasNumericKeys()
    {
        ArrayUtils::hasNumericKeys([
            '1' => 'value',
        ]);
    }
}
