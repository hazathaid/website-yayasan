<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->segment(1) === 'en' ? 'en' : config('app.default_locale');

        app()->setLocale($locale);

        return $next($request);
    }
}
