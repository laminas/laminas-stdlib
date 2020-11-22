<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib;

/**
 * @template TContent
 * @template TMetaKey of array-key
 * @template TMetaValue
 */
interface MessageInterface
{
    /**
     * Set metadata
     *
     * @param  string|int|iterable<string|int, mixed> $spec
     * @param  mixed $value
     * @return $this
     *
     * @psalm-param TMetaKey|iterable<TMetaKey, TMetaValue> $spec
     * @psalm-param TMetaValue|null $value
     */
    public function setMetadata($spec, $value = null);

    /**
     * Get metadata
     *
     * @param  null|string|int $key
     * @return mixed
     *
     * @psalm-param TMetaKey|null $key
     * @psalm-return ($key is null ? iterable<TMetaKey, TMetaValue> : (TMetaValue|null))
     */
    public function getMetadata($key = null);

    /**
     * Set content
     *
     * @param  mixed $content
     * @return $this
     *
     * @psalm-param TContent $content
     */
    public function setContent($content);

    /**
     * Get content
     *
     * @return mixed
     *
     * @psalm-return TContent
     */
    public function getContent();
}
