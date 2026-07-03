<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SlowRequestDetector {

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response {
        $start = microtime(true);

        $response = $next($request);

        $duration = round((microtime(true) - $start) * 1000, 2);

        $threshold = config('app.slow_request_threshold', 500);

        if ($duration >= $threshold) {
            Log::channel('slow_request')->warning('Slow Request', [
                'request_id' => $request->attributes->get('request_id'),
                'duration_ms' => $duration,
                'status'      => $response->getStatusCode(),
                'method'      => $request->method(),
                'url'         => $request->fullUrl(),
                'route'       => optional($request->route())->getName(),
                'action'      => optional($request->route())->getActionName(),
                'ip'          => $request->ip(),
                'user_id'     => optional($request->user())->id,
                'memory_mb'   => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
                'user_agent' => $request->userAgent(),
                'os' => \request('os', null),
                'version' => \request('version', null),
                'query' => $request->query(),
            ]);
        }

        return $response;
    }
}
