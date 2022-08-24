<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\StaticAnalysis;

use Laminas\Stdlib\SplPriorityQueue;

use function array_values;
use function iterator_to_array;

final class SplPriorityQueueGenericsCanBeUnderstood
{
    /** @var SplPriorityQueue<string, int> */
    private SplPriorityQueue $laminas;

    /**
     * @param SplPriorityQueue<string, int> $laminas
     */
    public function __construct(
        SplPriorityQueue $laminas
    ) {
        $this->laminas = $laminas;
    }

    /** @return list<string> */
    public function laminasList(): array
    {
        return array_values(iterator_to_array($this->laminas));
    }
}
