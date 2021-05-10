<?php

namespace Laminas\Stdlib\Hydrator\Aggregate;

use Laminas\Hydrator\Aggregate\AggregateHydrator as BaseAggregateHydrator;
use Laminas\Stdlib\Hydrator\HydratorInterface;

/**
 * Aggregate hydrator that composes multiple hydrators via events
 *
 * @deprecated Use Laminas\Hydrator\Aggregate\AggregateHydrator from laminas/laminas-hydrator instead.
 */
class AggregateHydrator extends BaseAggregateHydrator implements HydratorInterface
{
    /**
     * {@inheritDoc}
     */
    public function extract($object)
    {
        $event = new ExtractEvent($this, $object);

        $this->getEventManager()->triggerEvent($event);

        return $event->getExtractedData();
    }

    /**
     * {@inheritDoc}
     */
    public function hydrate(array $data, $object)
    {
        $event = new HydrateEvent($this, $object, $data);

        $this->getEventManager()->triggerEvent($event);

        return $event->getHydratedObject();
    }
}
