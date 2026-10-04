<?php

namespace App\Http\Data\Admin\Notification;

use App\Core\Domain\Admin\Models\Admin;
use App\Core\Domain\Notification\Models\Notification;
use App\Http\Data\WithApiValidator;
use App\Http\Data\WithIndexData;
use App\Models\User;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;

class NotificationIndexData extends Data
{
    use WithApiValidator;
    use WithIndexData;

    public function __construct(
        #[Max(160)]
        public ?string $title = null,
        public ?string $user_type = null,
        public ?int $user_id = null,
        public ?bool $is_global = null,
    ) {}

    public static function rules(): array
    {
        return [
            'id' => ['nullable', 'integer', 'min:1'],
            'user_type' => ['nullable', 'string', Rule::in([Admin::MORPH_NAME, User::MORPH_NAME])],
            'user_id' => ['nullable', 'integer', 'min:1'],
            'is_global' => ['nullable', 'boolean'],
            'page' => ['nullable', 'integer', 'min:1'],
            'page_rows' => ['nullable', 'integer', 'min:1', 'max:100'],
            'search' => ['nullable', 'string', 'max:160'],
            'sort' => ['nullable', 'string', Rule::in([
                Notification::ID, Notification::TITLE, Notification::CREATED_AT, Notification::UPDATED_AT,
            ])],
            'sort_type' => ['nullable', 'string', Rule::in(['asc', 'desc'])],
        ];
    }
}
