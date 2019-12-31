<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib\Hydrator;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class DelegatingHydratorFactory implements FactoryInterface
{
    /**
     * Creates DelegatingHydrator
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return DelegatingHydrator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        // Assume that this factory is registered with the HydratorManager,
        // and just pass it directly on.
        return new DelegatingHydrator($serviceLocator);
    }
}
