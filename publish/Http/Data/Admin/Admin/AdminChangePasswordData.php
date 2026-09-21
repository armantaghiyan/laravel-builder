<?php

namespace App\Http\Data\Admin\Admin;

use App\Http\Data\WithApiValidator;
use Spatie\LaravelData\Attributes\Validation\Confirmed;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class AdminChangePasswordData extends Data {

	use WithApiValidator;

	#[Required, Min(6), Max(40)]
	public string $old_password;

	#[Required, Min(6), Max(40)]
	#[Confirmed]
	public string $new_password;

	#[Required, Min(6), Max(40)]
	public string $new_password_confirmation;
}
