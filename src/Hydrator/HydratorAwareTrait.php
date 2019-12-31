<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib\Hydrator;

trait HydratorAwareTrait
{
    /**
     * Hydrator instance
     *
     * @var HydratorInterface
     * @access protected
     */
    protected $hydrator = null;

    /**
     * Set hydrator
     *
     * @param  HydratorInterface $hydrator
     * @return self
     * @access public
     */
    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;

        return $this;
    }

    /**
     * Retrieve hydrator
     *
     * @param void
     * @return null|HydratorInterface
     * @access public
     */
    public function getHydrator()
    {
        return $this->hydrator;
    }
}
