<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Stdlib\SignalHandlers;

class InstanceMethod
{
    public function handler()
    {
        return __FUNCTION__;
    }

    public static function staticHandler()
    {
        return __FUNCTION__;
    }
}
