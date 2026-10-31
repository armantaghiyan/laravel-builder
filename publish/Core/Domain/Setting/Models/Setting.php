<?php

namespace App\Core\Domain\Setting\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {

    public const TB = 'settings';

    public const KEY = 'key';

    public const VALUE = 'value';

    protected $primaryKey = self::KEY;

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [self::KEY, self::VALUE];

    protected function casts(): array {
        return [self::VALUE => 'json'];
    }
}
