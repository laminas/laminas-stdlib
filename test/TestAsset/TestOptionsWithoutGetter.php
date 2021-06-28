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

    /**
     * @param mixed $value
     */
    public function setFoo($value): void
    {
        $this->foo = $value;
    }
}
