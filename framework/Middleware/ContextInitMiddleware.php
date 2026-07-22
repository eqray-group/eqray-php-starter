<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Middleware;

use Framework\DI\ContextBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class ContextInitMiddleware implements MiddlewareInterface
{
    protected RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function handle(Request $request, callable $next): Response
    {
        $this->requestStack->push($request);

        ContextBag::set('request', $request);

        try {
            return $next($request);
        } finally {
            $this->requestStack->pop();
            ContextBag::clear();
        }
    }
}
