<?php

namespace App\Core\Application\Actions\LogFile;

use App\Core\Domain\Logger\Repositories\LogFileRepository;

readonly class LogFileDownloadAction {

    public function __construct(private LogFileRepository $logFileRepository) { }

    public function execute(string $path): string {
        return $this->logFileRepository->file($path);
    }
}
