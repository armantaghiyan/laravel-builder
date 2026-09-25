<?php

namespace App\Core\Infrastructure\Services;

use App\Core\Domain\Logger\Constants\LogEvent;
use App\Core\Domain\Logger\Constants\LogLevel;
use App\Core\Domain\Logger\Models\Log;
use App\Core\Domain\Logger\Repositories\LogRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Logger {

	public function __construct(
		private readonly LogRepository $logs,
	) {
	}

	public function log(
		LogEvent $event,
		?string  $message = null,
		?Model   $loggable = null,
		LogLevel $level = LogLevel::Info,
		?array   $metadata = null,
	): Log {
		[$userId, $userGuard] = $this->authenticatedUser();
		[$ipAddress, $userAgent] = $this->requestInformation();

		return $this->logs->create([
			Log::USER_ID => $userId,
			Log::USER_GUARD => $userGuard,
			Log::EVENT => $event,
			Log::LEVEL => $level,
			Log::MESSAGE => $message,
			Log::LOGGABLE_TYPE => $loggable?->getMorphClass(),
			Log::LOGGABLE_ID => $loggable?->getKey(),
			Log::METADATA => $metadata,
			Log::IP_ADDRESS => $ipAddress,
			Log::USER_AGENT => $userAgent,
			Log::IS_REVIEWED => in_array($level, [LogLevel::Info->value, LogLevel::Warning->value]) ? 1 : 0,
		]);
	}

	/**
	 * @return array{0: int|string|null, 1: string|null}
	 */
	private function authenticatedUser(): array {
		$guards = array_unique(array_filter([
			Auth::getDefaultDriver(),
			...array_keys(config('auth.guards', [])),
		]));

		foreach ($guards as $guardName) {
			$guard = Auth::guard($guardName);

			if ($guard->check()) {
				/** @var Authenticatable|null $user */
				$user = $guard->user();

				if ($user !== null) {
					return [$user->getAuthIdentifier(), $guardName];
				}
			}
		}

		return [null, null];
	}

	/**
	 * @return array{0: string|null, 1: string|null}
	 */
	private function requestInformation(): array {
		if (!app()->bound(Request::class)) {
			return [null, null];
		}

		$request = app(Request::class);

		return [$request->ip(), Str::limit($request->userAgent(), 256, '')];
	}
}
