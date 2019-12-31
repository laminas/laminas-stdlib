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
class TestOptionsWithoutGetter extends AbstractOptions
{
    protected $foo;

    public function setFoo($value)
    {
        $this->foo = $value;
    }
}
