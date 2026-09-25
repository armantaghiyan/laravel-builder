<?php

namespace App\Http\Data\Admin\Log;

use App\Http\Data\WithApiValidator;
use App\Http\Data\WithIndexData;
use Spatie\LaravelData\Data;

class LogIndexData extends Data {

    use WithApiValidator;
    use WithIndexData;

    public function __construct(
        public $user_id,
        public $user_guard,
        public $event,
        public $level,
        public $message,
        public $loggable_type,
        public $loggable_id,
        public $ip_address,
        public $user_agent,
        public $is_reviewed,
    ) {

    }
}
