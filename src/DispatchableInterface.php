<?php

declare(strict_types=1);

namespace Laminas\Stdlib;

/**
 * @deprecated since 3.21.0; Will be removed in 4.0.0.
 */
interface DispatchableInterface
{
    /**
     * Dispatch a request
     *
     * @return Response|mixed
     */
    public function dispatch(RequestInterface $request, ?ResponseInterface $response = null);
}
