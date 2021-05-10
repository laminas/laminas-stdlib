<?php

namespace Laminas\Stdlib\Hydrator;

use Laminas\Hydrator\HydratorPluginManager as BaseHydratorPluginManager;

/**
 * Plugin manager implementation for hydrators.
 *
 * Enforces that adapters retrieved are instances of HydratorInterface
 *
 * @deprecated Use Laminas\Hydrator\HydratorPluginManager from laminas/laminas-hydrator instead.
 */
class HydratorPluginManager extends BaseHydratorPluginManager
{
    /**
     * Default aliases
     *
     * @var array
     */
    protected $aliases = [
        'delegatinghydrator' => 'Laminas\Stdlib\Hydrator\DelegatingHydrator',

        // Legacy Zend Framework aliases

        // v2 normalized FQCNs
    ];

    /**
     * Default set of adapters
     *
     * @var array
     */
    protected $invokableClasses = [
        'arrayserializable' => 'Laminas\Stdlib\Hydrator\ArraySerializable',
        'classmethods'      => 'Laminas\Stdlib\Hydrator\ClassMethods',
        'objectproperty'    => 'Laminas\Stdlib\Hydrator\ObjectProperty',
        'reflection'        => 'Laminas\Stdlib\Hydrator\Reflection'
    ];

    /**
     * Default factory-based adapters
     *
     * @var array
     */
    protected $factories = [
        'Laminas\Stdlib\Hydrator\DelegatingHydrator' => 'Laminas\Stdlib\Hydrator\DelegatingHydratorFactory',
        'laminasstdlibhydratordelegatinghydrator'    => 'Laminas\Stdlib\Hydrator\DelegatingHydratorFactory',
    ];
}
