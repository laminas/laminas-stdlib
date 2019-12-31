<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\TestAsset;

/**
 * Test asset to check how optional parameters of are treated methods
 */
class ClassMethodsOptionalParameters
{
    /**
     * @var string
     */
    public $foo = 'bar';

    /**
     * @param mixed $optional
     *
     * @return string
     */
    public function getFoo($optional = null)
    {
        return $this->foo;
    }

    /**
     * @param string $foo
     * @param mixed  $optional
     */
    public function setFoo($foo, $optional = null)
    {
        $this->foo = (string) $foo;
    }
}
