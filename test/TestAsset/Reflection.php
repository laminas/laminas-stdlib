<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\TestAsset;

class Reflection
{
    public $foo = '1';

    protected $fooBar = '2';

    private $fooBarBaz = '3';

    public function getFooBar()
    {
        return $this->fooBar;
    }

    public function getFooBarBaz()
    {
        return $this->fooBarBaz;
    }
}
