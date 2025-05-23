<?php

namespace App\Services\Domain\User\Admin\Resources;

use App\Helpers\ResponseManager;
use App\Services\Domain\User\Access\Resources\PermissionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminStartResource extends JsonResource {

    use ResponseManager;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {

        return $this->cast([
            RK_ADMIN => new AdminResource($this[RK_ADMIN]),
            RK_PERMISSIONS => PermissionResource::collection($this[RK_PERMISSIONS]),
            RK_ADMIN_PERMISSIONS => PermissionResource::collection($this[RK_ADMIN_PERMISSIONS]),
        ]);
    }
}
