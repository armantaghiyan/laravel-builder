<?php

namespace App\Http\Resources\User\Access;

use App\Helpers\ResponseManager;
use App\Http\Resources\Models\User\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccessUpdateResource extends JsonResource {

    use ResponseManager;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {

        return $this->cast([
            RK_ITEM => new RoleResource($this[RK_ITEM]),
        ]);
    }
}
