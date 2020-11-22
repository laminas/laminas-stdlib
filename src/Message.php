<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib;

use Traversable;

use function array_key_exists;
use function get_class;
use function gettype;
use function is_array;
use function is_object;
use function is_scalar;
use function sprintf;

/**
 * @template TContent
 * @template TMetaKey of array-key
 * @template TMetaValue
 * @template-implements MessageInterface<TContent, TMetaKey, TMetaValue>
 */
class Message implements MessageInterface
{
    /**
     * @var array<string|int, mixed>
     * @psalm-var array<TMetaKey, TMetaValue>
     */
    protected $metadata = [];

    /**
     * @var mixed
     * @psalm-var TContent
     */
    protected $content = '';

    /**
     * Set message metadata
     *
     * Non-destructive setting of message metadata; always adds to the metadata, never overwrites
     * the entire metadata container.
     *
     * @param  string|int|iterable<string|int, mixed> $spec
     * @param  mixed $value
     * @throws Exception\InvalidArgumentException
     * @return $this
     *
     * @psalm-param TMetaKey|iterable<TMetaKey, TMetaValue> $spec
     * @psalm-param TMetaValue|null $value
     * @psalm-suppress DocblockTypeContradiction
     */
    public function setMetadata($spec, $value = null)
    {
        if (is_scalar($spec)) {
            /** @psalm-var TMetaKey $spec */
            $this->metadata[$spec] = $value;
            return $this;
        }

        if (! is_array($spec) && ! $spec instanceof Traversable) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Expected a string, array, or Traversable argument in first position; received "%s"',
                (is_object($spec) ? get_class($spec) : gettype($spec))
            ));
        }
        foreach ($spec as $key => $value) {
            $this->metadata[$key] = $value;
        }
        return $this;
    }

    /**
     * Retrieve all metadata or a single metadatum as specified by key
     *
     * @param  null|string|int $key
     * @param  null|mixed $default
     * @throws Exception\InvalidArgumentException
     * @return mixed
     *
     * @template D
     * @psalm-param TMetaKey|null $key
     * @psalm-param D $default
     * @psalm-return ($key is null ? array<TMetaKey, TMetaValue> : TMetaValue|D)
     * @psalm-suppress DocblockTypeContradiction
     */
    public function getMetadata($key = null, $default = null)
    {
        if (null === $key) {
            return $this->metadata;
        }

        if (! is_scalar($key)) {
            throw new Exception\InvalidArgumentException('Non-scalar argument provided for key');
        }

        /** @psalm-var TMetaKey $key */
        if (array_key_exists($key, $this->metadata)) {
            return $this->metadata[$key];
        }

        return $default;
    }

    /**
     * Set message content
     *
     * @param  mixed $value
     * @return $this
     *
     * @psalm-param TContent $value
     */
    public function setContent($value)
    {
        $this->content = $value;
        return $this;
    }

    /**
     * Get message content
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function toString()
    {
        $request = '';
        foreach ($this->getMetadata() as $key => $value) {
            $request .= sprintf(
                "%s: %s\r\n",
                (string) $key,
                (string) $value
            );
        }
        $request .= "\r\n" . $this->getContent();
        return $request;
    }
}
