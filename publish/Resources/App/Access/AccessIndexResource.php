<?php

namespace App\Http\Resources\App\Access;

use App\Helpers\ResponseManager;
use App\Http\Resources\Models\App\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccessIndexResource extends JsonResource {

    use ResponseManager;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {

        return $this->cast([
            RK_ITEMS => RoleResource::collection($this[RK_ITEMS]),
        ]);
    }
}
