<?php

namespace App\Http\Resources\Admin\Log;

use App\Http\Resources\ResponseManager;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogReportResource extends JsonResource {

    public function __construct(
        public $data,
    ) {
        parent::__construct($data);
    }

    public function toArray(Request $request): array {
        return (new ResponseManager())->cast($this->data);
    }
}
