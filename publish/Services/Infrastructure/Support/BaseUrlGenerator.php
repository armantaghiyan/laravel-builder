<?php

namespace App\Services\Infrastructure\Support;

class BaseUrlGenerator {

    public function get(string $prefix, bool $withHttp = false): string {
        $url = explode('//', config('app.url'));
        return ($withHttp ? $url[0] . '//' : '') . ($prefix ? "$prefix." : '') . $url[1];
    }
}
