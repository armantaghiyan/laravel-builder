<?php

namespace App\Http\Controllers\Admin;

use App\Core\Application\Actions\LogFile\LogFileDownloadAction;
use App\Core\Application\Actions\LogFile\LogFileIndexAction;
use App\Core\Application\Actions\LogFile\LogFileShowAction;
use App\Http\Constants\Permissions;
use App\Http\Data\Admin\LogFile\LogFileDownloadData;
use App\Http\Data\Admin\LogFile\LogFileIndexData;
use App\Http\Data\Admin\LogFile\LogFileShowData;
use App\Http\Resources\Admin\LogFile\LogFileIndexResource;
use App\Http\Resources\Admin\LogFile\LogFileShowResource;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

#[Middleware('permission:' . Permissions::LOG_INDEX)]
class LogFileController extends Controller {

    public function __construct(
        private readonly LogFileIndexAction    $indexAction,
        private readonly LogFileShowAction     $showAction,
        private readonly LogFileDownloadAction $downloadAction,
    ) {
    }

    public function index(LogFileIndexData $data): LogFileIndexResource {
        return new LogFileIndexResource($this->indexAction->execute($data->path ?? ''));
    }

    public function show(LogFileShowData $data): LogFileShowResource {
        return new LogFileShowResource($this->showAction->execute($data));
    }

    public function download(LogFileDownloadData $data): BinaryFileResponse {
        $file = $this->downloadAction->execute($data->path);

        return response()->download($file, basename($file), [
            'Content-Type' => 'application/octet-stream',
            'Cache-Control' => 'private, no-store',
        ]);
    }
}
