<?php

namespace Laminas\Stdlib\Hydrator;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * @deprecated Use Laminas\Hydrator\DelegatingHydratorFactory from laminas/laminas-hydrator instead.
 */
class DelegatingHydratorFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        // Assume that this factory is registered with the HydratorManager,
        // and just pass it directly on.
        return new DelegatingHydrator($serviceLocator);
    }
}
