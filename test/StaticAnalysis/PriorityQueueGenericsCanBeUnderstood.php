<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib\StaticAnalysis;

use Laminas\Stdlib\PriorityQueue;

use function array_values;
use function iterator_to_array;

final class PriorityQueueGenericsCanBeUnderstood
{
    /**
     * @param PriorityQueue<string, int> $laminas
     */
    public function __construct(private PriorityQueue $laminas)
    {
    }

    /** @return list<string> */
    public function laminasList(): array
    {
        return array_values(iterator_to_array($this->laminas));
    }
}
