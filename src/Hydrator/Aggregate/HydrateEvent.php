<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib\Hydrator\Aggregate;

use Laminas\EventManager\Event;

/**
 * Event triggered when the {@see \Laminas\Stdlib\Hydrator\Aggregate\AggregateHydrator} hydrates
 * data into an object
 */
class HydrateEvent extends Event
{
    const EVENT_HYDRATE = 'hydrate';

    /**
     * {@inheritDoc}
     */
    protected $name = self::EVENT_HYDRATE;

    /**
     * @var object
     */
    protected $hydratedObject;

    /**
     * @var array
     */
    protected $hydrationData;

    /**
     * @param object $target
     * @param object $hydratedObject
     * @param array  $hydrationData
     */
    public function __construct($target, $hydratedObject, array $hydrationData)
    {
        $this->target         = $target;
        $this->hydratedObject = $hydratedObject;
        $this->hydrationData  = $hydrationData;
    }

    /**
     * Retrieves the object that is being hydrated
     *
     * @return object
     */
    public function getHydratedObject()
    {
        return $this->hydratedObject;
    }

    /**
     * @param object $hydratedObject
     */
    public function setHydratedObject($hydratedObject)
    {
        $this->hydratedObject = $hydratedObject;
    }

    /**
     * Retrieves the data that is being used for hydration
     *
     * @return array
     */
    public function getHydrationData()
    {
        return $this->hydrationData;
    }

    /**
     * @param array $hydrationData
     */
    public function setHydrationData(array $hydrationData)
    {
        $this->hydrationData = $hydrationData;
    }
}
