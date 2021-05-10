<?php

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
