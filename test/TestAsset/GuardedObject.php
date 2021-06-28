<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\TestAsset;

use Laminas\Stdlib\Guard\AllGuardsTrait;

class GuardedObject
{
    use AllGuardsTrait;

    /** @param mixed $value */
    public function setArrayOrTraversable($value)
    {
        $this->guardForArrayOrTraversable($value);
    }

    /** @param mixed $value */
    public function setNotEmpty($value)
    {
        $this->guardAgainstEmpty($value);
    }

    /** @param mixed $value */
    public function setNotNull($value)
    {
        $this->guardAgainstNull($value);
    }
}
