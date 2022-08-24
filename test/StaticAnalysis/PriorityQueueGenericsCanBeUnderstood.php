<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\StaticAnalysis;

use Laminas\Stdlib\PriorityQueue;

use function array_values;
use function iterator_to_array;

final class PriorityQueueGenericsCanBeUnderstood
{
    /** @var PriorityQueue<string, int> */
    private PriorityQueue $laminas;

    /**
     * @param PriorityQueue<string, int> $laminas
     */
    public function __construct(
        PriorityQueue $laminas
    ) {
        $this->laminas = $laminas;
    }

    /** @return list<string> */
    public function laminasList(): array
    {
        return array_values(iterator_to_array($this->laminas));
    }
}
