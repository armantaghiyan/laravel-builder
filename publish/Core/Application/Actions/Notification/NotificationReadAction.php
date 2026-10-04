<?php

namespace App\Core\Application\Actions\Notification;

use App\Core\Domain\Notification\Models\Notification;
use App\Core\Domain\Notification\Repositories\NotificationRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

readonly class NotificationReadAction
{
    public function __construct(
        private NotificationRepository $notificationRepository,
    ) {}

    public function execute(int $id, Model $user): Notification
    {
        return DB::transaction(function () use ($id, $user): Notification {
            $item = $this->notificationRepository->findVisibleOrError($id, $user);
            if (! $item[Notification::IS_READ]) {
                $this->notificationRepository->update($item, [
                    Notification::IS_READ => 1,
                ]);
            }

            return $item;
        });
    }
}
