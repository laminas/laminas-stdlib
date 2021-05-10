<?php

namespace Laminas\Stdlib\Hydrator;

use Laminas\Hydrator\HydratorInterface as BaseHydratorInterface;
use Laminas\Stdlib\Extractor\ExtractionInterface;

/**
 * @deprecated Use Laminas\Hydrator\HydratorInterface from laminas/laminas-hydrator instead.
 */
interface HydratorInterface extends BaseHydratorInterface, HydrationInterface, ExtractionInterface
{
}
