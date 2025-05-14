<?php

namespace App\Http\Resources\User\Admin;

use App\Helpers\ResponseManager;
use App\Http\Resources\Models\AdminResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminStoreResource extends JsonResource {

    use ResponseManager;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {

        return $this->cast([
            RK_ITEM => new AdminResource($this[RK_ITEM]),
        ]);
    }
}
