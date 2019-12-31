<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib\Hydrator;

use Laminas\Stdlib\Hydrator\Filter\FilterComposite;
use Laminas\Stdlib\Hydrator\Filter\FilterInterface;
use Laminas\Stdlib\Hydrator\Filter\FilterProviderInterface;

interface FilterEnabledInterface extends FilterProviderInterface
{
    /**
     * Add a new filter to take care of what needs to be hydrated.
     * To exclude e.g. the method getServiceLocator:
     *
     * <code>
     * $composite->addFilter(
     *     "servicelocator",
     *     function ($property) {
     *         list($class, $method) = explode('::', $property);
     *         if ($method === 'getServiceLocator') {
     *             return false;
     *         }
     *         return true;
     *     },
     *     FilterComposite::CONDITION_AND
     * );
     * </code>
     *
     * @param string $name Index in the composite
     * @param callable|FilterInterface $filter
     * @param int $condition
     * @return FilterComposite
     */
    public function addFilter($name, $filter, $condition = FilterComposite::CONDITION_OR);

    /**
     * Check whether a specific filter exists at key $name or not
     *
     * @param string $name Index in the composite
     * @return bool
     */
    public function hasFilter($name);

    /**
     * Remove a filter from the composition.
     * To not extract "has" methods, you simply need to unregister it
     *
     * <code>
     * $filterComposite->removeFilter('has');
     * </code>
     *
     * @param $name
     * @return FilterComposite
     */
    public function removeFilter($name);
}
