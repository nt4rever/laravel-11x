<?php

namespace App\Http\Middleware;

use Closure;
use Context;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserRequestId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get existing id or generate new one.
        $requestId = $request->header('X-Request-Id') ?? (string) \Str::ulid();

        // Add to context.
        Context::add('request_id', $requestId);

        /** @var \Illuminate\Http\Response $response */
        $response = $next($request);

        return $response->header('X-Request-Id', $requestId);
    }
}
