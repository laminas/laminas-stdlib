<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\TestAsset;

use Laminas\Stdlib\Guard\AllGuardsTrait;

class GuardedObject
{
    use AllGuardsTrait;

    public function setArrayOrTraversable(mixed $value): void
    {
        $this->guardForArrayOrTraversable($value);
    }

    public function setNotEmpty(mixed $value): void
    {
        $this->guardAgainstEmpty($value);
    }

    public function setNotNull(mixed $value): void
    {
        $this->guardAgainstNull($value);
    }
}
