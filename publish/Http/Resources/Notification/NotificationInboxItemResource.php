<?php

namespace App\Http\Resources\Notification;

use App\Core\Domain\Notification\Models\Notification;
use App\Core\Shared\Helper\DateHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationInboxItemResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			Notification::ID => $this[Notification::ID],
			Notification::TITLE => $this[Notification::TITLE],
			Notification::MESSAGE => $this[Notification::MESSAGE],
			Notification::URL => $this[Notification::URL],
			'is_global' => $this[Notification::USER_ID] === null,
			Notification::IS_READ => $this[Notification::IS_READ],
			Notification::CREATED_AT => DateHelper::convert($this[Notification::CREATED_AT]),
		];
	}
}
