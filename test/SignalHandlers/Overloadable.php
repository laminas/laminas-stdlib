<?php

namespace LaminasTest\Stdlib\SignalHandlers;

class Overloadable
{
    public function __call($method, $args)
    {
        return $method;
    }
}
