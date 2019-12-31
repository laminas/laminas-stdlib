<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib\Hydrator\Aggregate;

use Laminas\EventManager\EventManager;
use Laminas\EventManager\EventManagerAwareInterface;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Stdlib\Hydrator\HydratorInterface;

/**
 * Aggregate hydrator that composes multiple hydrators via events
 */
class AggregateHydrator implements HydratorInterface, EventManagerAwareInterface
{
    const DEFAULT_PRIORITY = 1;

    /**
     * @var \Laminas\EventManager\EventManagerInterface|null
     */
    protected $eventManager;

    /**
     * Attaches the provided hydrator to the list of hydrators to be used while hydrating/extracting data
     *
     * @param \Laminas\Stdlib\Hydrator\HydratorInterface $hydrator
     * @param int                                     $priority
     */
    public function add(HydratorInterface $hydrator, $priority = self::DEFAULT_PRIORITY)
    {
        $this->getEventManager()->attachAggregate(new HydratorListener($hydrator), $priority);
    }

    /**
     * {@inheritDoc}
     */
    public function extract($object)
    {
        $event = new ExtractEvent($this, $object);

        $this->getEventManager()->trigger($event);

        return $event->getExtractedData();
    }

    /**
     * {@inheritDoc}
     */
    public function hydrate(array $data, $object)
    {
        $event = new HydrateEvent($this, $object, $data);

        $this->getEventManager()->trigger($event);

        return $event->getHydratedObject();
    }

    /**
     * {@inheritDoc}
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers([__CLASS__, get_class($this)]);

        $this->eventManager = $eventManager;
    }

    /**
     * {@inheritDoc}
     */
    public function getEventManager()
    {
        if (null === $this->eventManager) {
            $this->setEventManager(new EventManager());
        }

        return $this->eventManager;
    }
}
