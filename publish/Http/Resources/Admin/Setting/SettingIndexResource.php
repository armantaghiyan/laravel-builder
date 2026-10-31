<?php

namespace App\Http\Resources\Admin\Setting;

use App\Http\Resources\ResponseManager;
use App\Http\Resources\Rk;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingIndexResource extends JsonResource {

    public function toArray(Request $request): array {
        return (new ResponseManager)->cast([
            Rk::ITEMS => $this->resource,
        ]);
    }
}
