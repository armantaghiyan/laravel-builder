<?php

namespace App\Http\Resources\User\Access;

use App\Helpers\ResponseManager;
use App\Http\Resources\Models\User\PermissionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccessShowResource extends JsonResource {

    use ResponseManager;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {

        return $this->cast([
            RK_PERMISSIONS => PermissionResource::collection($this[RK_PERMISSIONS]),
        ]);
    }
}
