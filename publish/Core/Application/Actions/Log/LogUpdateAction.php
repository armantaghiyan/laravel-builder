<?php

namespace App\Core\Application\Actions\Log;

use App\Core\Domain\Logger\Models\Log;
use App\Core\Domain\Logger\Repositories\LogRepository;
use App\Http\Data\Admin\Log\LogUpdateData;

readonly class LogUpdateAction {

    public function __construct(
        private LogRepository $logRepository,
    ) {
    }

    public function execute(LogUpdateData $data, int $id): \Illuminate\Database\Eloquent\Model {
        $log = $this->logRepository->findOrErrorById($id);

        $this->logRepository->update($log, [
            Log::IS_REVIEWED => $data->is_reviewed,
        ]);

        return $log;
    }
}
