<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\TestAsset;

use Laminas\Stdlib\AbstractOptions;

/**
 * Dummy TestOptions used to test Stdlib\Options
 */
class TestOptionsWithoutGetter extends AbstractOptions
{
    /** @var mixed */
    protected $foo;

    public function setFoo(mixed $value): void
    {
        $this->foo = $value;
    }
}
