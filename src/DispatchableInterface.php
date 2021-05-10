<?php

namespace Laminas\Stdlib;

interface DispatchableInterface
{
    /**
     * Dispatch a request
     *
     * @param RequestInterface $request
     * @param null|ResponseInterface $response
     * @return Response|mixed
     */
    public function dispatch(RequestInterface $request, ResponseInterface $response = null);
}
