<?php

namespace App\Http\Controllers\Admin;

use App\Http\Constants\Permissions;
use App\Http\Data\Admin\Log\LogIndexData;
use App\Http\Resources\Admin\Log\LogIndexResource;
use App\Http\Resources\Admin\Log\LogShowResource;
use App\Core\Application\Actions\Log\LogIndexAction;
use App\Core\Application\Actions\Log\LogShowAction;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Routing\Controller;

class LogController extends Controller {

    public function __construct(
        private readonly LogIndexAction $indexAction,
        private readonly LogShowAction  $showAction,
    ) {
    }

    #[Middleware('permission:' . Permissions::LOG_INDEX)]
    public function index(LogIndexData $data): LogIndexResource {
        [$items, $count] = $this->indexAction->execute($data);

        return new LogIndexResource($items, $count);
    }


    #[Middleware('permission:' . Permissions::LOG_INDEX)]
    public function show($id): LogShowResource {
        $item = $this->showAction->execute($id);

        return new LogShowResource($item);
    }
}
