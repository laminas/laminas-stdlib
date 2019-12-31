<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib\Hydrator\Iterator;

use Iterator;
use IteratorIterator;
use Laminas\Stdlib\Exception\InvalidArgumentException;
use Laminas\Stdlib\Hydrator\HydratorInterface;

class HydratingIteratorIterator extends IteratorIterator implements HydratingIteratorInterface
{
    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * @var object
     */
    protected $prototype;

    /**
     * @param HydratorInterface $hydrator
     * @param Iterator $data
     * @param string|object $prototype Object or class name to use for prototype.
     */
    public function __construct(HydratorInterface $hydrator, Iterator $data, $prototype)
    {
        $this->setHydrator($hydrator);
        $this->setPrototype($prototype);
        parent::__construct($data);
    }

    /**
     * @inheritdoc
     */
    public function setPrototype($prototype)
    {
        if (is_object($prototype)) {
            $this->prototype = $prototype;
            return;
        }

        if (!class_exists($prototype)) {
            throw new InvalidArgumentException(
                sprintf('Method %s was passed an invalid class name: %s', __METHOD__, $prototype)
            );
        }

        $this->prototype = new $prototype;
    }

    /**
     * @inheritdoc
     */
    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    /**
     * @return object Returns hydrated clone of $prototype
     */
    public function current()
    {
        $currentValue = parent::current();
        $object       = clone $this->prototype;
        $this->hydrator->hydrate($currentValue, $object);
        return $object;
    }
}
