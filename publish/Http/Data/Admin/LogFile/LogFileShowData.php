<?php

namespace App\Http\Data\Admin\LogFile;

use App\Http\Data\WithApiValidator;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class LogFileShowData extends Data {

    use WithApiValidator;

    public function __construct(
        #[Required, Max(1024)]
        public string $path,

        #[IntegerType, Min(1), Max(5000)]
        public int    $lines = 100,
    ) {
    }
}
