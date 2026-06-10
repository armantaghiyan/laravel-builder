<?php

namespace App\Http\Resources;

class ResponseManager {

    public function cast(array $data = []): array {

        return [
            'result' => 'success',
            'data' => $data,
        ];
    }
}
