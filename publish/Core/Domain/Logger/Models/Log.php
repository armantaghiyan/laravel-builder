<?php

namespace App\Core\Domain\Logger\Models;

use App\Core\Domain\Common\Models\BaseModel;
use App\Core\Domain\Logger\Constants\LogEvent;
use App\Core\Domain\Logger\Constants\LogLevel;
use App\Core\Domain\Logger\Constants\LogLoggableType;
use App\Core\Domain\Logger\Constants\LogUserGuard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Log extends Model
{
	use BaseModel;

	public const TB = 'logs';

	public const ID = 'id';

	public const USER_ID = 'user_id';

	public const USER_GUARD = 'user_guard';

	public const EVENT = 'event';

	public const LEVEL = 'level';

	public const MESSAGE = 'message';

	public const LOGGABLE_TYPE = 'loggable_type';

	public const LOGGABLE_ID = 'loggable_id';

	public const METADATA = 'metadata';

	public const IP_ADDRESS = 'ip_address';

	public const USER_AGENT = 'user_agent';

	public const IS_REVIEWED = 'is_reviewed';

	public const CREATED_AT = 'created_at';

	public const UPDATED_AT = 'updated_at';

	protected $table = self::TB;

	protected $fillable = [
		Log::USER_ID,
		Log::USER_GUARD,
		Log::EVENT,
		Log::LEVEL,
		Log::MESSAGE,
		Log::LOGGABLE_TYPE,
		Log::LOGGABLE_ID,
		Log::METADATA,
		Log::IP_ADDRESS,
		Log::USER_AGENT,
		Log::IS_REVIEWED,
	];

	protected function casts(): array
	{
		return [
			Log::LEVEL => LogLevel::class,
			Log::USER_GUARD => LogUserGuard::class,
			Log::EVENT => LogEvent::class,
			Log::LOGGABLE_TYPE => LogLoggableType::class,
		];
	}

	public function loggable(): MorphTo
	{
		return $this->morphTo();
	}
}
