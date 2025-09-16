<?php

declare(strict_types=1);

namespace Laminas\Stdlib;

/**
 * @deprecated since 3.21.0 due to the retirement of Laminas MVC. Will be removed in 4.0.0.
 * Use Psr\Http\Message\RequestInterface instead
 *
 * @see https://www.php-fig.org/psr/psr-7/
 * @see https://github.com/laminas/laminas-diactoros as a possible implementation.
 */
interface RequestInterface extends MessageInterface
{
}
