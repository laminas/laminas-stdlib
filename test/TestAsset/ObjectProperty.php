<?php

namespace LaminasTest\Stdlib\TestAsset;

/**
 * @group      Laminas_Stdlib
 */
class ObjectProperty
{
    public $foo = null;
    public $bar = null;
    public $blubb = null;
    public $quo = null;
    protected $quin = null;

    public function __construct()
    {
        $this->foo = "bar";
        $this->bar = "foo";
        $this->blubb = "baz";
        $this->quo = "blubb";
        $this->quin = 'five';
    }

}
