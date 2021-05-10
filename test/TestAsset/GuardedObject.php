<?php

namespace LaminasTest\Stdlib\TestAsset;

use Laminas\Stdlib\Guard\AllGuardsTrait;

class GuardedObject
{
    use AllGuardsTrait;

    public function setArrayOrTraversable($value)
    {
        $this->guardForArrayOrTraversable($value);
    }

    public function setNotEmpty($value)
    {
        $this->guardAgainstEmpty($value);
    }

    public function setNotNull($value)
    {
        $this->guardAgainstNull($value);
    }
}
