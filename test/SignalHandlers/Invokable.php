<?php

namespace LaminasTest\Stdlib\SignalHandlers;

class Invokable
{
    public function __invoke()
    {
        return __FUNCTION__;
    }
}
