<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

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
}
