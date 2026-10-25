<?php

namespace App\Http\Data\Admin\LogFile;

use App\Http\Data\WithApiValidator;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class LogFileDownloadData extends Data {

    use WithApiValidator;

    public function __construct(
        #[Required, Max(1024)]
        public string $path,
    ) {
    }
}
