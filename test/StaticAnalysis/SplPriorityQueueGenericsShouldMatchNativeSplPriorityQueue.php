<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\StaticAnalysis;

use Laminas\Stdlib\SplPriorityQueue;
use SplPriorityQueue as NativeSplPriorityQueue;

use function array_values;
use function iterator_to_array;

final class SplPriorityQueueGenericsShouldMatchNativeSplPriorityQueue
{
    /** @var SplPriorityQueue<int, string> */
    private SplPriorityQueue $laminas;
    /** @var NativeSplPriorityQueue<int, string> */
    private NativeSplPriorityQueue $native;

    /**
     * @param SplPriorityQueue<int, string>       $laminas
     * @param NativeSplPriorityQueue<int, string> $native
     */
    public function __construct(
        SplPriorityQueue $laminas,
        NativeSplPriorityQueue $native
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
