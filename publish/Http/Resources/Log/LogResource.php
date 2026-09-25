<?php

namespace App\Http\Resources\Admin\Log;

use App\Core\Domain\Admin\Models\Admin;
use App\Core\Domain\Log\Models\Log;
use App\Core\Shared\Helper\DateHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogResource extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {

        return [
            Log::ID => $this->whenHas(Log::ID,$this[Log::ID]),
			Log::USER_ID => $this->whenHas(Log::USER_ID,$this[Log::USER_ID]),

            Log::EVENT => $this->whenHas(Log::EVENT,$this[Log::EVENT]),
            Log::EVENT . '_text' => $this->whenHas(Log::EVENT, $this[Log::EVENT]?->label()),

            Log::USER_GUARD => $this->whenHas(Log::USER_GUARD,$this[Log::USER_GUARD]),
            Log::USER_GUARD . '_text' => $this->whenHas(Log::USER_GUARD, $this[Log::USER_GUARD]?->label()),
            Log::USER_GUARD . '_color' => $this->whenHas(Log::USER_GUARD, $this[Log::USER_GUARD]?->color()),

            Log::LEVEL => $this->whenHas(Log::LEVEL,$this[Log::LEVEL]),
            Log::LEVEL . '_text' => $this->whenHas(Log::LEVEL, $this[Log::LEVEL]?->label()),
            Log::LEVEL . '_color' => $this->whenHas(Log::LEVEL, $this[Log::LEVEL]?->color()),

            Log::LOGGABLE_TYPE => $this->whenHas(Log::LOGGABLE_TYPE,$this[Log::LOGGABLE_TYPE]),
            Log::LOGGABLE_TYPE . '_text' => $this->whenHas(Log::LOGGABLE_TYPE, $this[Log::LOGGABLE_TYPE]?->label()),

			Log::LOGGABLE_ID => $this->whenHas(Log::LOGGABLE_ID,$this[Log::LOGGABLE_ID]),

            Log::MESSAGE => $this->whenHas(Log::MESSAGE,$this[Log::MESSAGE]),
			Log::METADATA => $this->whenHas(Log::METADATA,$this[Log::METADATA]),
			Log::IP_ADDRESS => $this->whenHas(Log::IP_ADDRESS,$this[Log::IP_ADDRESS]),
			Log::USER_AGENT => $this->whenHas(Log::USER_AGENT,$this[Log::USER_AGENT]),

            Log::IS_REVIEWED => $this->whenHas(Log::IS_REVIEWED,$this[Log::IS_REVIEWED]),
            Log::IS_REVIEWED . '_text' => $this->whenHas(Log::IS_REVIEWED, __('enum.yes_or_no.' . $this[Log::IS_REVIEWED])),

            Log::CREATED_AT => $this->whenHas(Log::CREATED_AT, DateHelper::convert($this[Log::CREATED_AT])),
            Log::UPDATED_AT => $this->whenHas(Log::UPDATED_AT, DateHelper::convert($this[Log::UPDATED_AT])),
        ];
    }
}
