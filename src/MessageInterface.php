<?php

declare(strict_types=1);

namespace Laminas\Stdlib;

use Traversable;

/**
 * @deprecated since 3.21.0 due to the retirement of Laminas MVC. Will be removed in 4.0.0.
 * Use Psr\Http\Message\MessageInterface instead
 *
 * @see https://www.php-fig.org/psr/psr-7/
 * @see https://github.com/laminas/laminas-diactoros as a possible implementation.
 */
interface MessageInterface
{
    /**
     * Set metadata
     *
     * @param string|int|array|Traversable $spec
     * @param  mixed $value
     */
    public function setMetadata($spec, $value = null);

    /**
     * Get metadata
     *
     * @param  null|string|int $key
     * @return mixed
     */
    public function getMetadata($key = null);

    /**
     * Set content
     *
     * @param  mixed $content
     * @return mixed
     */
    public function setContent($content);

    /**
     * Get content
     *
     * @return mixed
     */
    public function getContent();
}
