<?php

namespace App\Models\User;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Admin extends Authenticatable {

    use HasFactory, Notifiable, HasApiTokens, BaseModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        COL_ADMIN_NAME,
        COL_ADMIN_USERNAME,
        COL_ADMIN_PASSWORD,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        COL_ADMIN_PASSWORD,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return [
            COL_ADMIN_PASSWORD => 'hashed',
        ];
    }

    //------------------------------------------------------------------------------------------------------------------
    //-----------------------------------------  Accessors and Mutators ------------------------------------------------
    //------------------------------------------------------------------------------------------------------------------
}
