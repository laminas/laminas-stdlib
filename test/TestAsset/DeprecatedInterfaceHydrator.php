<?php

namespace LaminasTest\Stdlib\TestAsset;

use Laminas\Stdlib\Hydrator\HydratorInterface;

class DeprecatedInterfaceHydrator implements HydratorInterface
{
    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
    }

    /**
     * Extract values from an object
     *
     * @param  object $object
     * @return array
     */
    public function extract($object)
    {
    }
} 
