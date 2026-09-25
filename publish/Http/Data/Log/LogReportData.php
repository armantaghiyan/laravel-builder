<?php

namespace App\Http\Data\Admin\Log;

use App\Http\Data\WithApiValidator;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Attributes\Validation\DateFormat;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\RequiredWith;
use Spatie\LaravelData\Data;

class LogReportData extends Data {

    use WithApiValidator;

    public function __construct(
        #[Nullable, RequiredWith('end'), DateFormat('Y-m-d')]
        public ?string $start = null,

        #[Nullable, RequiredWith('start'), DateFormat('Y-m-d'), AfterOrEqual('start')]
        public ?string $end = null,
    ) {
    }
}
