<?php

namespace App\Core\Domain\Logger\Repositories;

use App\Core\Domain\Common\Repositories\BaseRepository;
use App\Core\Domain\Logger\Models\Log;
use App\Http\Data\Admin\Log\LogIndexData;

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

        return [$items, $count];
    }
}
