<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib\Hydrator;

use Laminas\ServiceManager\ServiceLocatorInterface;

class DelegatingHydrator implements HydratorInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $hydrators;

    /**
     * Constructor
     *
     * @param ServiceLocatorInterface $hydrators
     */
    public function __construct(ServiceLocatorInterface $hydrators)
    {
        $this->hydrators = $hydrators;
    }

    /**
     * {@inheritdoc}
     */
    public function hydrate(array $data, $object)
    {
        return $this->getHydrator($object)->hydrate($data, $object);
    }

    /**
     * {@inheritdoc}
     */
    public function extract($object)
    {
        return $this->getHydrator($object)->extract($object);
    }

    /**
     * Gets hydrator of an object
     *
     * @param  object $object
     * @return HydratorInterface
     */
    protected function getHydrator($object)
    {
        return $this->hydrators->get(get_class($object));
    }
}
