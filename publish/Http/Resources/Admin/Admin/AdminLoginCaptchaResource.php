<?php

namespace App\Http\Resources\Admin\Admin;

use App\Http\Resources\ResponseManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminLoginCaptchaResource extends JsonResource {

    public function toArray(Request $request): array {
        return (new ResponseManager)->cast($this->resource);
    }

    public function withResponse(Request $request, JsonResponse $response): void {
        $response->headers->set('Cache-Control', 'no-store, private');
    }
}
