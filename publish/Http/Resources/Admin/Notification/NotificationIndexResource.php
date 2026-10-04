<?php

namespace App\Http\Resources\Admin\Notification;

use App\Http\Resources\ResponseManager;
use App\Http\Resources\Rk;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationIndexResource extends JsonResource
{
    public function __construct(
        public $items,
        public $count,
    ) {
        parent::__construct($items);
    }

    public function toArray(Request $request): array
    {
        return (new ResponseManager)->cast([
            Rk::ITEMS => NotificationResource::collection($this->items),
            Rk::COUNT => $this->count,
        ]);
    }
}
