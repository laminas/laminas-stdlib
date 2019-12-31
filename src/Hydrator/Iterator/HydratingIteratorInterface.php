<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib\Hydrator\Iterator;

use Iterator;
use Laminas\Stdlib\Hydrator\HydratorInterface;

interface HydratingIteratorInterface extends Iterator
{
    /**
     * This sets the prototype to hydrate.
     *
     * This prototype can be the name of the class or the object itself;
     * iteration will clone the object.
     *
     * @param string|object $prototype
     */
    public function setPrototype($prototype);

    /**
     * Sets the hydrator to use during iteration.
     *
     * @param HydratorInterface $hydrator
     */
    public function setHydrator(HydratorInterface $hydrator);
}
