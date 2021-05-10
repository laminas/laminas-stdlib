<?php

namespace LaminasTest\Stdlib\TestAsset;

use Laminas\Stdlib\AbstractOptions;

/**
 * Dummy TestOptions used to test Stdlib\Options
 */
class TestOptionsNoStrict extends AbstractOptions
{
    protected $__strictMode__ = false;

    protected $testField;

    public function setTestField($value)
    {
        $this->testField = $value;
    }

    public function getTestField()
    {
        return $this->testField;
    }
}
