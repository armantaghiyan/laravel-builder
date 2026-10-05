<?php

namespace App\Core\Application\Actions\Log;

use App\Core\Domain\Logger\Repositories\LogRepository;
use App\Http\Data\Log\LogIndexData;

readonly class LogIndexAction {

	public function __construct(
		private LogRepository $logRepository,
	) {
	}

	public function execute(LogIndexData $data): array {
		return $this->logRepository->index($data);
	}
}
