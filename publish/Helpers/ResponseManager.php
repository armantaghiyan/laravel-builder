<?php

namespace App\Helpers;

trait ResponseManager {

    public function cast($data = []) {

        return [
            'result' => 'success',
            'data' => $data,
        ];
    }
}
