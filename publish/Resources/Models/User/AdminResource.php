<?php

namespace App\Http\Resources\Models\User;

use App\Models\User\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class AdminResource extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {

        return [
            Admin::ID => $this[Admin::ID],
            Admin::NAME => $this->whenHas(Admin::NAME, $this[Admin::NAME]),
            Admin::USERNAME => $this->whenHas(Admin::USERNAME, $this[Admin::USERNAME]),
            Admin::IMAGE => $this->whenHas(Admin::IMAGE, $this[Admin::IMAGE]),
            Admin::LAST_LOGIN => $this->whenHas(Admin::LAST_LOGIN, Carbon::parse($this[Admin::LAST_LOGIN])->format('Y-m-d H:i')),
            Admin::CREATED_AT => $this->whenHas(Admin::CREATED_AT, Carbon::parse($this[Admin::CREATED_AT])->format('Y-m-d H:i')),
            Admin::UPDATED_AT => $this->whenHas(Admin::UPDATED_AT, Carbon::parse($this[Admin::UPDATED_AT])->format('Y-m-d H:i')),
        ];
    }
}
