<?php

namespace App\Core\Application\Actions\Log;

use App\Core\Domain\Log\Repositories\LogRepository;
use App\Http\Data\Admin\Log\LogIndexData;

readonly class LogIndexAction {

    public function __construct(
        private LogRepository $logRepository,
    ) {
    }

    public function execute(LogIndexData $data): array {
        return $this->logRepository->index($data);
    }
}
