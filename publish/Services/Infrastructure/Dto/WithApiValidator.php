<?php

namespace App\Services\Infrastructure\Dto;

use Illuminate\Validation\Validator;
use ValidationException;

trait WithApiValidator {

    public static function withValidator(Validator $validator): void {
        if ($validator->fails()) {
            if ($validator->fails()) {
                throw new ValidationException($validator->errors());
            }
        }
    }
}
