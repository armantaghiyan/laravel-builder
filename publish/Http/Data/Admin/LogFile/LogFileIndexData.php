<?php

namespace App\Http\Data\Admin\LogFile;

use App\Http\Data\WithApiValidator;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;

class LogFileIndexData extends Data {

    use WithApiValidator;

    public function __construct(
        #[Max(1024)]
        public ?string $path = null,
    ) {
    }
}
