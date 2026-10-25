<?php

namespace App\Http\Resources\Admin\Access;

use App\Core\Domain\Admin\Models\Admin;
use App\Http\Resources\ResponseManager;
use App\Http\Resources\Rk;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccessAdminIndexResource extends JsonResource
{
    public function __construct(
        public $items,
        public $count,
    ) {
        parent::__construct($items);
    }

    public function toArray(Request $request): array
    {
        return (new ResponseManager)->cast([
            Rk::ITEMS => $this->items->map(fn (Admin $admin) => $admin->only([
                Admin::ID, Admin::NAME, Admin::USERNAME,
            ])),
            Rk::COUNT => $this->count,
        ]);
    }
}
