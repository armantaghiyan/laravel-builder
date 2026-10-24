<?php

namespace App\Http\Resources\Admin\Admin;

use App\Core\Shared\Helper\DateHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminSessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'is_current' => $this->id === $request->user('admin')->currentAccessToken()?->getKey(),
            'created_at' => DateHelper::convert($this->created_at),
            'last_used_at' => $this->last_used_at ? DateHelper::convert($this->last_used_at) : null,
            'expires_at' => $this->expires_at ? DateHelper::convert($this->expires_at) : null,
        ];
    }
}
