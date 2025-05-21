<?php

namespace App\Http\Resources\GlobalResources;

use App\Helpers\ResponseManager;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessResource extends JsonResource {

    use ResponseManager;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {

        return $this->cast();
    }
}
