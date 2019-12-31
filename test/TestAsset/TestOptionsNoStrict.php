<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\TestAsset;

use Laminas\Stdlib\AbstractOptions;

/**
 * Dummy TestOptions used to test Stdlib\Options
 */
class TestOptionsNoStrict extends AbstractOptions
{
    // @codingStandardsIgnoreStart
    protected $__strictMode__ = false;
    // @codingStandardsIgnoreEnd

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
