<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            \App\Http\Middleware\Locale::class,
            \App\Http\Middleware\UserRequestId::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Global exception filter (HTTP Exception).
        $exceptions->render(function (HttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], $e->getStatusCode());
            }
        });

        // Global exception filter (Error Exception).
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                logger()->error('Internal Server Error', [
                    'error' => [
                        'message' => $e->getMessage(),
                    ],
                    'url' => $request->fullUrl(),
                ]);

                return response()->json([
                    'message' => __('message.internal_server_error'),
                    'request_id' => context('request_id'),
                    'path' => $request->path(),
                    'timestamp' => now()->toIso8601String(),
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        });
    })->create();
