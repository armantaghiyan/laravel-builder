<?php

namespace App\Http\Data\Notification;

use App\Http\Data\WithApiValidator;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;

class NotificationInboxData extends Data
{
    use WithApiValidator;

    public function __construct(
        #[Min(1)]
        public int $page = 1,
        #[Min(1), Max(100)]
        public int $page_rows = 7,
        public ?bool $is_read = null,
    ) {}
}
