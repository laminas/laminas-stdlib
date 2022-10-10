<?php

declare(strict_types=1);

namespace Laminas\Stdlib\ArrayUtils;

final class MergeReplaceKey implements MergeReplaceKeyInterface
{
    /**
     * @param mixed $data
     */
    public function __construct(protected $data)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getData()
    {
        return $this->data;
    }
}
