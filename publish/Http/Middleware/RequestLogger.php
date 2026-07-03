<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RequestLogger {

    public function handle(Request $request, Closure $next): Response {
        $start = microtime(true);

        $response = $next($request);

        $duration = round((microtime(true) - $start) * 1000, 2);
        $status = $response->getStatusCode();

        if ($status >= 400) {

            $data = [
                'request_id' => $request->attributes->get('request_id'),
                'time' => now()->toDateTimeString(),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'route' => optional($request->route())->getName(),
                'action' => optional($request->route())->getActionName(),

                'status' => $status,
                'duration_ms' => $duration,

                'ip' => $request->ip(),
                'user_id' => optional($request->user())->id,
                'user_agent' => $request->userAgent(),

                'query' => $request->query(),

                'os' => \request('os', null),
                'version' => \request('version', null),
            ];

            Log::channel('error_requests')->error('HTTP Error Request', $data);
        }

        return $response;
    }
}
