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

        Context::add('request_id', $requestId);

        \Log::withContext([
            'request-id' => $requestId,
            'url' => $request->fullUrl(),
        ]);

        return tap($next($request), function ($response) use ($requestId) {
            /** @var \Illuminate\Http\Response $response */
            $response->headers->set('X-Request-Id', $requestId);
        });
    }
}
