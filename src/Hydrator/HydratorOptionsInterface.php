<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib\Hydrator;

interface HydratorOptionsInterface
{
    /**
     * @param  array|\Traversable $options
     * @return HydratorOptionsInterface
     */
    public function setOptions($options);
}
