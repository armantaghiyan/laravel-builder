<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TrimStrings {

    protected array $except = [
        'password',
        'password_confirmation',
        'current_password',
    ];

    public function handle(Request $request, Closure $next): Response {
        try {
            $input = $request->all();

            if (!empty($input)) {
                $trimmed = $this->trimRecursively($input);
                $request->merge($trimmed);
            }
        } catch (Throwable $e) {
            report($e);
        }

        return $next($request);
    }

    protected function trimRecursively(array $data, string $prefix = ''): array {
        foreach ($data as $key => $value) {
            $fieldName = $prefix ? "{$prefix}.{$key}" : $key;

            if (is_array($value)) {
                $data[$key] = $this->trimRecursively($value, $fieldName);
            } elseif (is_string($value) && !in_array($key, $this->except, true)) {
                try {
                    $data[$key] = trim($value);
                } catch (Throwable $e) {

                }
            }
        }

        return $data;
    }
}
