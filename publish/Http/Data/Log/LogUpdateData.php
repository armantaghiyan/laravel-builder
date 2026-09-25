<?php

namespace App\Http\Data\Admin\Log;

use App\Http\Data\WithApiValidator;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class LogUpdateData extends Data {

    use WithApiValidator;

    public function __construct(
        #[Required, IntegerType, In([0, 1])]
        public int $is_reviewed,
    ) {
    }
}
