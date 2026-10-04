<?php

namespace App\Http\Resources\Admin\Notification;

use App\Core\Domain\Notification\Models\Notification;
use App\Core\Shared\Helper\DateHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            Notification::ID => $this[Notification::ID],
            Notification::TITLE => $this[Notification::TITLE],
            Notification::MESSAGE => $this[Notification::MESSAGE],
            Notification::URL => $this[Notification::URL],
            Notification::USER_TYPE => $this[Notification::USER_TYPE],
            Notification::USER_ID => $this[Notification::USER_ID],
            'is_global' => $this[Notification::USER_TYPE] === null && $this[Notification::USER_ID] === null,
            'user_name' => $this->whenLoaded('user', fn () => $this->user?->name),
            Notification::IS_READ => $this[Notification::IS_READ],
            Notification::CREATED_AT => DateHelper::convert($this[Notification::CREATED_AT]),
            Notification::UPDATED_AT => DateHelper::convert($this[Notification::UPDATED_AT]),
        ];
    }
}
