<?php

namespace LaminasTest\Stdlib\TestAsset;

use Laminas\Stdlib\Hydrator\HydratorInterface;

/**
 * This test asset exists to see how deprecation works; it is associated with
 * the test LaminasTest\Stdlib\HydratorDeprecationTest.
 */
class HydratorInjectedObject
{
    public $hydrator;

    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
    }
}
