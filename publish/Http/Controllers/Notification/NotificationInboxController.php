<?php

namespace App\Http\Controllers\Notification;

use App\Core\Application\Actions\Notification\NotificationInboxAction;
use App\Core\Application\Actions\Notification\NotificationReadAction;
use App\Http\Data\Notification\NotificationInboxData;
use App\Http\Resources\Notification\NotificationInboxResource;
use App\Http\Resources\Notification\NotificationReadResource;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NotificationInboxController extends Controller
{
    public function __construct(
        private readonly NotificationInboxAction $inboxAction,
        private readonly NotificationReadAction $readAction,
    ) {}

    public function index(NotificationInboxData $data, Request $request): NotificationInboxResource
    {
        [$items, $count, $unreadCount] = $this->inboxAction->execute($data, $request->user());

        return new NotificationInboxResource($items, $count, $unreadCount);
    }

    public function read(int $id, Request $request): NotificationReadResource
    {
        return new NotificationReadResource($this->readAction->execute($id, $request->user()));
    }
}
