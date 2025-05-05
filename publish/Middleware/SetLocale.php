<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale {

    /**
     * Handle an incoming request.
     *
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response {
        $locale = $request->header('Accept-Language', 'en');

        if (strlen($locale) < 3) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
