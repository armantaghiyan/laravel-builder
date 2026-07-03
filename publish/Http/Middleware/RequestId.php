<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class RequestId {

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response {
        $requestId = $request->header('X-Request-Id');

        if (!$requestId) {
            $requestId = (string)Str::uuid();
        }

        $request->attributes->set('request_id', $requestId);

        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('X-Request-Id', $requestId);

        return $response;
    }
}
