<?php

namespace App\Services\Domain\User\Admin\Resources;

use App\Helpers\ResponseManager;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminIndexResource extends JsonResource {

    use ResponseManager;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {

        return $this->cast([
            RK_ITEMS => AdminResource::collection($this[RK_ITEMS]),
            RK_COUNT => $this[RK_COUNT],
        ]);
    }
}
