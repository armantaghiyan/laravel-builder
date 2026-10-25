<?php

namespace App\Http\Rules;

use App\Core\Domain\Admin\Repositories\AdminLoginCaptchaRepository;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class AdminLoginCaptchaRule implements DataAwareRule, ValidationRule {

    private array $data = [];

    private ?bool $valid = null;

    public function __construct(private readonly AdminLoginCaptchaRepository $captchas) { }

    public function setData(array $data): static {
        if ($data !== $this->data) {
            $this->valid = null;
        }

        $this->data = $data;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void {
        $id = $this->data['captcha_id'] ?? null;

        // The Data validator may evaluate the same rule more than once per request.
        $this->valid ??= is_string($id) && Str::isUuid($id) && is_string($value)
            && $this->captchas->consume($id, $value, request()->ip() ?? 'unknown');

        if (!$this->valid) {
            $fail(__('error.captcha_invalid'));
        }
    }
}
