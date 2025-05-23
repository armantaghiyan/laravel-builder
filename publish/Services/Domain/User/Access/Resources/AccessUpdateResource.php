<?php

namespace App\Services\Domain\User\Access\Resources;

use App\Helpers\ResponseManager;
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
