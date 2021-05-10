<?php

namespace LaminasTest\Stdlib\TestAsset;

use Laminas\Stdlib\Hydrator\HydrationInterface;

/**
 * This test asset exists to see how deprecation works; it is associated with
 * the test LaminasTest\Stdlib\HydratorDeprecationTest.
 */
class HydratorInjectedObjectUsingDeprecatedHydrationInterfaceTypehint
{
    public $hydrator;

    public function setHydrator(HydrationInterface $hydrator)
    {
        $this->hydrator = $hydrator;
    }
}
