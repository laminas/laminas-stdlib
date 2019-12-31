<?php

/**
 * @see       https://github.com/laminas/laminas-stdlib for the canonical source repository
 * @copyright https://github.com/laminas/laminas-stdlib/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-stdlib/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Stdlib;

use Laminas\Stdlib\RequestInterface as Request;
use Laminas\Stdlib\ResponseInterface as Response;

interface DispatchableInterface
{
    /**
     * Dispatch a request
     *
     * @param Request $request
     * @param null|Response $response
     * @return Response|mixed
     */
    public function dispatch(Request $request, Response $response = null);
}
