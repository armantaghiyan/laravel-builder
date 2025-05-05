<?php

namespace App\Http\Resources\Models\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {

        return [
            COL_ADMIN_ID => $this[COL_ADMIN_ID],
            COL_ADMIN_NAME => $this->whenHas(COL_ADMIN_NAME, $this[COL_ADMIN_NAME]),
            COL_ADMIN_USERNAME => $this->whenHas(COL_ADMIN_USERNAME, $this[COL_ADMIN_USERNAME]),
            COL_ADMIN_IMAGE => $this->whenHas(COL_ADMIN_IMAGE, $this[COL_ADMIN_IMAGE]),
            COL_ADMIN_LAST_LOGIN => $this->whenHas(COL_ADMIN_LAST_LOGIN, $this[COL_ADMIN_LAST_LOGIN]),
            COL_ADMIN_CREATED_AT => $this->whenHas(COL_ADMIN_CREATED_AT, $this[COL_ADMIN_CREATED_AT]),
            COL_ADMIN_UPDATED_AT => $this->whenHas(COL_ADMIN_UPDATED_AT, $this[COL_ADMIN_UPDATED_AT]),
        ];
    }
}
