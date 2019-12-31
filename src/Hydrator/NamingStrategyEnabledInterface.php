<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib\Hydrator;

use Laminas\Stdlib\Hydrator\NamingStrategy\NamingStrategyInterface;

interface NamingStrategyEnabledInterface
{
    /**
     * Adds the given naming strategy
     *
     * @param NamingStrategyInterface $strategy The naming to register.
     * @return NamingStrategyEnabledInterface
     */
    public function setNamingStrategy(NamingStrategyInterface $strategy);

    /**
     * Gets the naming strategy.
     *
     * @return NamingStrategyInterface
     */
    public function getNamingStrategy();

    /**
     * Checks if a naming strategy exists.
     *
     * @return bool
     */
    public function hasNamingStrategy();

    /**
     * Removes the naming with the given name.
     *
     * @return NamingStrategyEnabledInterface
     */
    public function removeNamingStrategy();
}
