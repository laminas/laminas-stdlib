<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\TestAsset;


class HydratorStrategyEntityB
{
    private $field1;
    private $field2;

    public function __construct($field1, $field2)
    {
        $this->field1 = $field1;
        $this->field2 = $field2;
    }

    public function getField1()
    {
        return $this->field1;
    }

    public function getField2()
    {
        return $this->field2;
    }

    public function setField1($value)
    {
        $this->field1 = $value;
        return $this;
    }

    public function setField2($value)
    {
        $this->field2 = $value;
        return $this;
    }
}
