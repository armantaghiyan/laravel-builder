<?php

namespace App\Core\Domain\Notification\Models;

use App\Core\Domain\Common\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    use BaseModel;

    public const TB = 'notifications';

    public const ID = 'id';

    public const TITLE = 'title';

    public const MESSAGE = 'message';

    public const URL = 'url';

    public const USER_TYPE = 'user_type';

    public const USER_ID = 'user_id';

    public const IS_READ = 'is_read';

    public const CREATED_AT = 'created_at';

    public const UPDATED_AT = 'updated_at';

    protected $table = self::TB;

    protected $fillable = [
        self::TITLE,
        self::MESSAGE,
        self::URL,
        self::USER_TYPE,
        self::USER_ID,
        self::IS_READ,
    ];

    protected function casts(): array
    {
        return [
            self::USER_ID => 'integer',
            self::IS_READ => 'integer',
        ];
    }

    public function user(): MorphTo
    {
        return $this->morphTo();
    }
}
