<?php

namespace App\Core\Application\Actions\LogFile;

use App\Core\Domain\Logger\Repositories\LogFileRepository;
use App\Http\Data\Admin\LogFile\LogFileShowData;

readonly class LogFileShowAction {

    public function __construct(private LogFileRepository $logFileRepository) { }

    public function execute(LogFileShowData $data): array {
        return $this->logFileRepository->show($data->path, $data->lines);
    }
}
