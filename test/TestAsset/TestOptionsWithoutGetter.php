<?php

namespace LaminasTest\Stdlib\TestAsset;

use Laminas\Stdlib\AbstractOptions;

/**
 * Dummy TestOptions used to test Stdlib\Options
 */
class TestOptionsWithoutGetter extends AbstractOptions
{
    protected $foo;

    public function setFoo($value)
    {
        $this->foo = $value;
    }
}
