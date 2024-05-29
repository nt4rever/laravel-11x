<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Locale
{
    private array $locales = [
        'en',
        'vi',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('X-Lang') && in_array($request->header('X-Lang', 'en'), $this->locales)) {
            $locale = $request->header('X-Lang');
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
