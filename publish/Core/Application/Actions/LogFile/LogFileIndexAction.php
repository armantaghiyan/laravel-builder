<?php

namespace App\Core\Application\Actions\LogFile;

use App\Core\Domain\Logger\Repositories\LogFileRepository;

readonly class LogFileIndexAction {

    public function __construct(private LogFileRepository $logFileRepository) { }

    public function execute(string $path): array {
        return $this->logFileRepository->index($path);
    }
}
