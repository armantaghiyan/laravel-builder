<?php

namespace App\Http\Data\Admin\Setting;

use App\Http\Data\WithApiValidator;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class SettingUpdateData extends Data {

    use WithApiValidator;

    public function __construct(
        #[Required, Max(100)]
        public string               $key,
        public string|int|bool|null $value,
    ) {
    }

    public static function rules(ValidationContext $context): array {
        $definitions = config('settings');
        $key = $context->payload['key'] ?? null;
        $definition = is_string($key) ? ($definitions[$key] ?? null) : null;

        return [
            'key' => ['required', 'string', 'max:100', Rule::in(array_keys($definitions))],
            'value' => $definition['rules'] ?? ['prohibited'],
        ];
    }
}
