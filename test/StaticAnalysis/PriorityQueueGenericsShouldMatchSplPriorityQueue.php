<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\StaticAnalysis;

use Laminas\Stdlib\PriorityQueue;
use SplPriorityQueue;

use function array_values;
use function iterator_to_array;

final class PriorityQueueGenericsShouldMatchSplPriorityQueue
{
    /** @var PriorityQueue<int, string> */
    private PriorityQueue $laminas;
    /** @var SplPriorityQueue<int, string> */
    private SplPriorityQueue $native;

    /**
     * @param PriorityQueue<int, string>    $laminas
     * @param SplPriorityQueue<int, string> $native
     */
    public function __construct(
        PriorityQueue $laminas,
        SplPriorityQueue $native
    ) {
        $this->laminas = $laminas;
        $this->native  = $native;
    }

    /** @return list<string> */
    public function laminasList(): array
    {
        return array_values(iterator_to_array($this->laminas));
    }

    /** @return list<string> */
    public function nativeList(): array
    {
        return array_values(iterator_to_array($this->native));
    }
}
