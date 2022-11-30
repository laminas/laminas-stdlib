<?php

declare(strict_types=1);

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\ArrayObject;

/**
 * @template TKey of array-key
 * @template TValue
 * @template-extends ArrayObject<TKey, TValue>
 */
final class CustomArrayObject extends ArrayObject
{
    private bool $isImmutable = true;

    public function isImmutable(): bool
    {
        return $this->isImmutable;
    }
}
