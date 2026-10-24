<?php

namespace App\Http\Controllers\Admin;

use App\Core\Application\Actions\Admin\AdminSessionDestroyAction;
use App\Core\Application\Actions\Admin\AdminSessionIndexAction;
use App\Http\Constants\Permissions;
use App\Http\Resources\Admin\Admin\AdminSessionIndexResource;
use App\Http\Resources\SuccessResource;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Routing\Controller;

class AdminSessionController extends Controller
{
    public function __construct(
        private readonly AdminSessionIndexAction $indexAction,
        private readonly AdminSessionDestroyAction $destroyAction,
    ) {}

    public function profileIndex(): AdminSessionIndexResource
    {
        return new AdminSessionIndexResource($this->indexAction->execute());
    }

    public function profileDestroy(int $id): SuccessResource
    {
        $this->destroyAction->execute($id);

        return new SuccessResource([]);
    }

    #[Middleware('permission:'.Permissions::ADMIN_INDEX)]
    public function index(int $adminId): AdminSessionIndexResource
    {
        return new AdminSessionIndexResource($this->indexAction->execute($adminId));
    }

    #[Middleware('permission:'.Permissions::ADMIN_UPDATE)]
    public function destroy(int $adminId, int $id): SuccessResource
    {
        $this->destroyAction->execute($id, $adminId);

        return new SuccessResource([]);
    }
}
