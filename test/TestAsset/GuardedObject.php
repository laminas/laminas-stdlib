<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

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
