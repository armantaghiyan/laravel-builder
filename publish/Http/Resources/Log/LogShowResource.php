<?php

namespace App\Http\Resources\Admin\Log;

use App\Http\Resources\Rk;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ResponseManager;

class LogShowResource extends JsonResource {

    public function __construct(
        public $item,
    ) {
        parent::__construct($item);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {

        return (new ResponseManager())->cast([
            Rk::ITEM => new LogResource($this->item),
        ]);
    }
}
