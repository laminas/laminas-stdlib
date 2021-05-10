<?php

namespace LaminasTest\Stdlib\TestAsset;

use Laminas\Stdlib\Hydrator\Strategy\DefaultStrategy;

class HydratorStrategyContextAware extends DefaultStrategy
{
    public $object;
    public $data;

    public function extract($value, $object = null)
    {
        $this->object = $object;
        return $value;
    }

    public function hydrate($value, $data = null)
    {
        $this->data = $data;
        return $value;
    }
}
