<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class WithTransaction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return rescue(fn() => DB::transaction(function () use ($request, $next) {
            $result = $next($request);

            if (isset($result->exception)) {
                throw $result->exception;
            }

            return $result;
        }), function (Throwable $e) {
            $result = [
                'message' => $e->getMessage(),
            ];

            return new JsonResponse($result, Response::HTTP_BAD_REQUEST);
        });
    }
}
