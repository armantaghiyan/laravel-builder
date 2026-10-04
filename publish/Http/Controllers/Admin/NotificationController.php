<?php

namespace App\Http\Controllers\Admin;

use App\Core\Application\Actions\Notification\NotificationDestroyAction;
use App\Core\Application\Actions\Notification\NotificationIndexAction;
use App\Core\Application\Actions\Notification\NotificationShowAction;
use App\Core\Application\Actions\Notification\NotificationStoreAction;
use App\Core\Application\Actions\Notification\NotificationUpdateAction;
use App\Http\Constants\Permissions;
use App\Http\Data\Admin\Notification\NotificationIndexData;
use App\Http\Data\Admin\Notification\NotificationStoreData;
use App\Http\Data\Admin\Notification\NotificationUpdateData;
use App\Http\Resources\Admin\Notification\NotificationIndexResource;
use App\Http\Resources\Admin\Notification\NotificationShowResource;
use App\Http\Resources\Admin\Notification\NotificationStoreResource;
use App\Http\Resources\Admin\Notification\NotificationUpdateResource;
use App\Http\Resources\SuccessResource;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Routing\Controller;

class NotificationController extends Controller
{
    public function __construct(
        private readonly NotificationIndexAction $indexAction,
        private readonly NotificationShowAction $showAction,
        private readonly NotificationStoreAction $storeAction,
        private readonly NotificationUpdateAction $updateAction,
        private readonly NotificationDestroyAction $destroyAction,
    ) {}

    #[Middleware('permission:'.Permissions::NOTIFICATION_INDEX)]
    public function index(NotificationIndexData $data): NotificationIndexResource
    {
        [$items, $count] = $this->indexAction->execute($data);

        return new NotificationIndexResource($items, $count);
    }

    #[Middleware('permission:'.Permissions::NOTIFICATION_STORE)]
    public function store(NotificationStoreData $data): NotificationStoreResource
    {
        return new NotificationStoreResource($this->storeAction->execute($data));
    }

    #[Middleware('permission:'.Permissions::NOTIFICATION_INDEX)]
    public function show(int $id): NotificationShowResource
    {
        return new NotificationShowResource($this->showAction->execute($id));
    }

    #[Middleware('permission:'.Permissions::NOTIFICATION_UPDATE)]
    public function update(NotificationUpdateData $data, int $id): NotificationUpdateResource
    {
        return new NotificationUpdateResource($this->updateAction->execute($data, $id));
    }

    #[Middleware('permission:'.Permissions::NOTIFICATION_DESTROY)]
    public function destroy(int $id): SuccessResource
    {
        $this->destroyAction->execute($id);

        return new SuccessResource([]);
    }
}
