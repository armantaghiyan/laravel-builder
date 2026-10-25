<?php

namespace App\Http\Resources\Admin\LogFile;

use App\Http\Resources\ResponseManager;
use App\Http\Resources\Rk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogFileIndexResource extends JsonResource {

    public function withResponse(Request $request, JsonResponse $response): void {
        $response->headers->set('Cache-Control', 'private, no-store');
    }

    public function toArray(Request $request): array {
        return (new ResponseManager)->cast([
            Rk::ITEMS => $this->resource,
        ]);
    }
}
