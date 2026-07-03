<?php

namespace App\Http\Middleware;

use App\Core\Domain\Common\Constants\StatusCodes;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps {

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response {
        if (
            !app()->environment('local')
            && !$request->isSecure()
        ) {
            return redirect()->secure(
                $request->getRequestUri(),
                StatusCodes::Moved_permanently
            );
        }

        return $next($request);
    }
}
