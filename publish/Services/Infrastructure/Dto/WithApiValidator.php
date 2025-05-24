<?php

namespace App\Services\Infrastructure\Dto;

use App\Services\Infrastructure\Exceptions\ValidationException;
use Illuminate\Validation\Validator;

trait WithApiValidator {

    public static function withValidator(Validator $validator): void {
        if ($validator->fails()) {
            if ($validator->fails()) {
                throw new ValidationException($validator->errors());
            }
        }
    }
}
