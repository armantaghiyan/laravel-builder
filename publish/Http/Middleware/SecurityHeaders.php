<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SecurityHeaders {

	public function handle(Request $request, Closure $next): Response {
		$response = $next($request);

		try {
			/*
			|--------------------------------------------------------------------------
			| Security headers
			|--------------------------------------------------------------------------
			*/

			$response->headers->set('X-Content-Type-Options', 'nosniff');
			$response->headers->set('X-Frame-Options', 'DENY');
			$response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

			$response->headers->set(
				'Permissions-Policy',
				'geolocation=(), microphone=(), camera=()'
			);

			/*
			|--------------------------------------------------------------------------
			| HSTS (only HTTPS)
			|--------------------------------------------------------------------------
			*/
			if ($request->isSecure()) {
				$response->headers->set(
					'Strict-Transport-Security',
					'max-age=31536000; includeSubDomains'
				);
			}

			/*
			|--------------------------------------------------------------------------
			| CSP (basic safe default)
			|--------------------------------------------------------------------------
			*/
			$response->headers->set(
				'Content-Security-Policy',
				"frame-ancestors 'none';"
			);

			/*
			|--------------------------------------------------------------------------
			| Remove sensitive / fingerprinting headers (Laravel-level)
			|--------------------------------------------------------------------------
			*/
			$response->headers->remove('X-Powered-By');
			$response->headers->remove('Server');
			$response->headers->remove('X-Generator');
			$response->headers->remove('X-AspNet-Version');
			$response->headers->remove('X-AspNetMvc-Version');
		} catch (Throwable $e) {
			report($e);
		}

		return $response;
	}
}
