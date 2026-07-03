<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ForceJsonResponse {

    public function handle(Request $request, Closure $next): Response {
        try {
            $request->headers->set('Accept', 'application/json');

        } catch (Throwable $e) {
            report($e);
        }

        return $next($request);
    }
}
