<?php

namespace App\Http\Data\Admin\Notification;

use App\Core\Domain\Admin\Models\Admin;
use App\Http\Data\WithApiValidator;
use App\Models\User;
use Closure;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class NotificationStoreData extends Data
{
	use WithApiValidator;

	public function __construct(
		#[Required, Min(1), Max(160)]
		public string $title,
		#[Required, Min(1), Max(10000)]
		public string $message,
		#[Required]
		public string $user_type,
		#[Max(2048)]
		public ?string $url = null,
		public ?int $user_id = null,
	) {}

	public static function rules(ValidationContext $context): array
	{
		$userType = $context->payload['user_type'] ?? null;
		$userIdRules = ['nullable', 'integer', 'min:1'];

		if (in_array($userType, [Admin::MORPH_NAME, User::MORPH_NAME], true)) {
			$userIdRules[] = Rule::exists($userType === Admin::MORPH_NAME ? Admin::TB : 'users', 'id');
		}

		return [
			'user_type' => ['required', 'string', Rule::in([Admin::MORPH_NAME, User::MORPH_NAME])],
			'user_id' => $userIdRules,
			'url' => ['bail', 'nullable', 'string', 'max:2048', function (string $attribute, mixed $value, Closure $fail): void {
				$internal = preg_match('/\A\/(?!\/)[^\s\\\\]*\z/u', $value) === 1;
				$external = preg_match('/\Ahttps?:\/\/[^\s\\\\]+\z/iu', $value) === 1
					&& filter_var($value, FILTER_VALIDATE_URL) !== false;

				if (! $internal && ! $external) {
					$fail(__('validation.url', ['attribute' => $attribute]));
				}
			}],
		];
	}
}
