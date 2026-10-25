<?php

namespace App\Http\Controllers\Admin;

use App\Core\Application\Actions\Admin\AdminLoginCaptchaAction;
use App\Http\Resources\Admin\Admin\AdminLoginCaptchaResource;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminLoginCaptchaController extends Controller {

    public function __invoke(Request $request, AdminLoginCaptchaAction $action): AdminLoginCaptchaResource {
        return new AdminLoginCaptchaResource($action->execute($request->ip() ?? 'unknown'));
    }
}
