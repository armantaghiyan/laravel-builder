<?php

namespace App\Core\Application\Actions\Log;

use App\Core\Domain\Logger\Repositories\LogRepository;

readonly class LogReportAction {

    public function __construct(
        private LogRepository $logRepository,
    ) {
    }

    public function execute(?string $startDate, ?string $endDate): array {
        return $this->logRepository->report($startDate, $endDate);
    }
}
