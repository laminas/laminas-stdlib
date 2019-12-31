<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib\Hydrator;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\Stdlib\Exception;

/**
 * Plugin manager implementation for hydrators.
 *
 * Enforces that adapters retrieved are instances of HydratorInterface
 */
class HydratorPluginManager extends AbstractPluginManager
{
    /**
     * Whether or not to share by default
     *
     * @var bool
     */
    protected $shareByDefault = false;

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
    ];

    /**
     * {@inheritDoc}
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof HydratorInterface) {
            // we're okay
            return;
        }

        throw new Exception\RuntimeException(sprintf(
            'Plugin of type %s is invalid; must implement Laminas\Stdlib\Hydrator\HydratorInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin))
        ));
    }
}
