<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\TestAsset;

use Laminas\Stdlib\AbstractOptions;

/**
 * Dummy TestOptions used to test Stdlib\Options
 *
 * @extends AbstractOptions<mixed>
 */
class TestOptionsWithoutGetter extends AbstractOptions
{
    protected mixed $foo;

    public function setFoo(mixed $value): void
    {
        $this->foo = $value;
    }
}
