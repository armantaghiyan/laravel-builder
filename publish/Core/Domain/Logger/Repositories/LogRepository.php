<?php

namespace App\Core\Domain\Logger\Repositories;

use App\Core\Domain\Common\Repositories\BaseRepository;
use App\Core\Domain\Logger\Constants\LogLevel;
use App\Core\Domain\Logger\Models\Log;
use App\Http\Data\Admin\Log\LogIndexData;
use Carbon\Carbon;

class LogRepository extends BaseRepository {

	public function model(): string {
		return Log::class;
	}

	public function index(LogIndexData $data): array {
		$query = Log::filter(Log::ID, $data->id)
			->filter(Log::USER_ID, $data->user_id)
			->filter(Log::USER_GUARD, $data->user_guard)
			->filter(Log::EVENT, $data->event)
			->filter(Log::LEVEL, $data->level)
			->filter(Log::MESSAGE, '%' . $data->message . '%')
			->filter(Log::LOGGABLE_TYPE, $data->loggable_type)
			->filter(Log::LOGGABLE_ID, $data->loggable_id)
			->filter(Log::IP_ADDRESS, $data->ip_address)
			->filter(Log::USER_AGENT, $data->user_agent)
			->filter(Log::IS_REVIEWED, $data->is_reviewed)
			->search([Log::MESSAGE, Log::IP_ADDRESS, Log::USER_AGENT, Log::ID, Log::USER_ID, Log::LOGGABLE_ID, Log::LOGGABLE_TYPE], $data->search);



		$count = $query->count();
		$items = $query->orderBy($data->sort, $data->sort_type)->page2($data->page_rows)->get();

		return [$items, $count, $this->statistics()];
	}

	/**
	 * @return array{labels: string[], values: int[], statistics: array{total: int, reviewed: int, unreviewed: int, reviewed_percentage: float}}
	 */
	public function report(?string $startDate, ?string $endDate): array {
		$counts = $this->reportQuery($startDate, $endDate)
			->selectRaw(Log::LEVEL . ', COUNT(*) as count')
			->groupBy(Log::LEVEL)
			->pluck('count', Log::LEVEL);

		return [
			'labels' => array_map(fn (LogLevel $level) => $level->label(), LogLevel::cases()),
			'values' => array_map(fn (LogLevel $level) => (int)($counts[$level->value] ?? 0), LogLevel::cases()),
			'statistics' => $this->statistics($startDate, $endDate),
		];
	}

	/**
	 * @return array{total: int, reviewed: int, unreviewed: int, reviewed_percentage: float}
	 */
	public function statistics(?string $startDate = null, ?string $endDate = null): array {
		$query = $this->reportQuery($startDate, $endDate);

		$total = (clone $query)->count();
		$reviewed = (clone $query)->where(Log::IS_REVIEWED, true)->count();
		$unreviewed = $total - $reviewed;

		return [
			'total' => $total,
			'reviewed' => $reviewed,
			'unreviewed' => $unreviewed,
			'reviewed_percentage' => $total ? round($reviewed / $total * 100, 1) : 0,
		];
	}

	private function reportQuery(?string $startDate, ?string $endDate) {
		$query = Log::query();

		if ($startDate && $endDate) {
			$query->whereBetween(Log::CREATED_AT, [
				Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay(),
				Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay(),
			]);
		}

		return $query;
	}
}
