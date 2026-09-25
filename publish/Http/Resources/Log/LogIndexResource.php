<?php

namespace App\Http\Resources\Admin\Log;

use App\Http\Resources\Rk;
use App\Http\Resources\ResponseManager;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class LogIndexResource extends JsonResource {

    public function __construct(
   		public $items,
   		public $count,
        public $statistics,
   	) {
   		parent::__construct($items);
   	}

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {

        return (new ResponseManager())->cast([
            Rk::ITEMS => LogResource::collection($this->items),
            Rk::COUNT => $this->count,
            Rk::STATISTICS => $this->statistics,
        ]);
    }
}
