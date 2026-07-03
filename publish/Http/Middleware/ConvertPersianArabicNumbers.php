<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ConvertPersianArabicNumbers {

    protected array $numberMap = [
        '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
        '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
        '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
        '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
    ];

    public function handle(Request $request, Closure $next): Response {
        try {
            $input = $request->all();

            if (!empty($input)) {
                $converted = $this->convertRecursively($input);
                $request->merge($converted);
            }

            $route = $request->route();
            if ($route) {
                $parameters = $route->parameters();
                foreach ($parameters as $key => $value) {
                    if (is_string($value)) {
                        $route->setParameter($key, $this->convertString($value));
                    }
                }
            }
        } catch (Throwable $e) {
            report($e);
        }

        return $next($request);
    }

    protected function convertRecursively(array $data): array {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->convertRecursively($value);
            } elseif (is_string($value)) {
                $data[$key] = $this->convertString($value);
            }
        }

        return $data;
    }

    protected function convertString(string $value): string {
        try {
            return strtr($value, $this->numberMap);
        } catch (Throwable $e) {
            return $value;
        }
    }
}
