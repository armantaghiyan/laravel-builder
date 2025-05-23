<?php

namespace App\Services\Infrastructure\Http;

class ResponseManager {

    public function cast(array $data = []): array {

        return [
            'result' => 'success',
            'data' => $data,
        ];
    }
}
