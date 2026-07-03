<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CaptureClientMeta {

	public function handle(Request $request, Closure $next): Response {
		$timestamp = $request->header('X-Timestamp');
		$appVersion = $request->header('X-App-Version');
		$os = $request->header('X-OS');

		$request->merge([
			'timestamp' => $timestamp,
			'version' => $appVersion,
			'os' => $os,
		]);

		return $next($request);
	}
}
