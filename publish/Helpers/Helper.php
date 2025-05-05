<?php

namespace App\Helpers;

class Helper {

    public static function getBaseUrl(string $prefix, bool $withHttp = false): string {
        $url = explode('//', config('app.url'));
        return ($withHttp ? $url[0] . '//' : '') . ($prefix ? "$prefix." : '') . $url[1];
    }
}
