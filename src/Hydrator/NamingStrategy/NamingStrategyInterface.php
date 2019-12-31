<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib\Hydrator\NamingStrategy;

/**
 * Allow property extraction / hydration for hydrator
 *
 * Interface PropertyStrategyInterface
 * @package Laminas\Stdlib\Hydrator\NamingStrategy
 */
interface NamingStrategyInterface
{
    /**
     * Converts the given name so that it can be extracted by the hydrator.
     *
     * @param string $name   The original name
     * @param object $object (optional) The original object for context.
     * @return mixed         The hydrated name
     */
    public function hydrate($name);

    /**
     * Converts the given name so that it can be hydrated by the hydrator.
     *
     * @param string $name The original name
     * @param array  $data (optional) The original data for context.
     * @return mixed The extracted name
     */
    public function extract($name);
}
