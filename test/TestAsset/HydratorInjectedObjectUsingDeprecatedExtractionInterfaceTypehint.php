<?php

namespace LaminasTest\Stdlib\TestAsset;

use Laminas\Stdlib\Extractor\ExtractionInterface;

/**
 * This test asset exists to see how deprecation works; it is associated with
 * the test LaminasTest\Stdlib\HydratorDeprecationTest.
 */
class HydratorInjectedObjectUsingDeprecatedExtractionInterfaceTypehint
{
    public $extractor;

    public function setExtractor(ExtractionInterface $extractor)
    {
        $this->extractor = $extractor;
    }
}
