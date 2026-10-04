<?php

namespace App\Http\Resources\Notification;

use App\Http\Resources\ResponseManager;
use App\Http\Resources\Rk;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationInboxResource extends JsonResource
{
    public function __construct(
        public $items,
        public $count,
        public $unreadCount,
    ) {
        parent::__construct($items);
    }

    public function toArray(Request $request): array
    {
        return (new ResponseManager)->cast([
            Rk::ITEMS => NotificationInboxItemResource::collection($this->items),
            Rk::COUNT => $this->count,
            Rk::UNREAD_COUNT => $this->unreadCount,
        ]);
    }
}
