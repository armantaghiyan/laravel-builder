<?php

namespace App\Core\Application\Actions\Log;

use App\Core\Domain\Log\Repositories\LogRepository;

readonly class LogShowAction {

    public function __construct(
        private LogRepository $logRepository,
    ) {
    }

    public function execute(int $id) {
        return $this->logRepository->findOrErrorById($id);
    }
}
