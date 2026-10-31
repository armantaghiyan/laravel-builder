<?php

namespace App\Http\Controllers\Admin;

use App\Core\Application\Actions\Setting\SettingIndexAction;
use App\Core\Application\Actions\Setting\SettingUpdateAction;
use App\Http\Constants\Permissions;
use App\Http\Data\Admin\Setting\SettingUpdateData;
use App\Http\Resources\Admin\Setting\SettingIndexResource;
use App\Http\Resources\SuccessResource;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Routing\Controller;

class SettingController extends Controller {

    public function __construct(
        private readonly SettingIndexAction  $indexAction,
        private readonly SettingUpdateAction $updateAction,
    ) {
    }

    #[Middleware('permission:' . Permissions::SETTING_INDEX)]
    public function index(): SettingIndexResource {
        return new SettingIndexResource($this->indexAction->execute());
    }

    #[Middleware('permission:' . Permissions::SETTING_UPDATE)]
    public function update(SettingUpdateData $data): SuccessResource {
        $this->updateAction->execute($data);

        return new SuccessResource([]);
    }
}
