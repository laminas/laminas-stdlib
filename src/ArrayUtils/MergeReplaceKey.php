<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib\ArrayUtils;

/**
 * @template TData
 * @template-implements MergeReplaceKeyInterface<TData>
 */
final class MergeReplaceKey implements MergeReplaceKeyInterface
{
    /**
     * @var mixed
     */
    protected $data;

    /**
     * @param mixed $data
     * @psalm-param TData $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getData()
    {
        return $this->data;
    }
}
