<?php

namespace App\Core\Domain\Admin\Models;

use App\Core\Domain\Common\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class Admin extends Authenticatable {

	use HasFactory, Notifiable, HasApiTokens, BaseModel, HasRoles;

	const TB = 'admins';
	const ID = 'id';
	const NAME = 'name';
	const USERNAME = 'username';
	const PASSWORD = 'password';
	const IMAGE = 'image';
	const LAST_LOGIN = 'last_login';
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		Faq::ID,
		Faq::NAME,
		Faq::USERNAME,
		Faq::PASSWORD,
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		Faq::PASSWORD,
	];

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array {
		return [
			Faq::PASSWORD => 'hashed',
		];
	}

	//------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------  Accessors and Mutators ------------------------------------------------
	//------------------------------------------------------------------------------------------------------------------
}
