<?php

namespace App\Http\Resources\Admin\Admin;

use App\Http\Resources\ResponseManager;
use App\Http\Resources\Rk;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminSessionIndexResource extends JsonResource
{
    public function __construct(public $items)
    {
        parent::__construct($items);
    }

    public function toArray(Request $request): array
    {
        return (new ResponseManager)->cast([
            Rk::ITEMS => AdminSessionResource::collection($this->items),
        ]);
    }
}
