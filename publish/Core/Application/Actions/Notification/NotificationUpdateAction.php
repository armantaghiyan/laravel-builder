<?php

namespace App\Core\Application\Actions\Notification;

use App\Core\Domain\Notification\Models\Notification;
use App\Core\Domain\Notification\Repositories\NotificationRepository;
use App\Http\Data\Admin\Notification\NotificationUpdateData;
use Illuminate\Support\Facades\DB;

readonly class NotificationUpdateAction
{
    public function __construct(
        private NotificationRepository $notificationRepository,
    ) {}

    public function execute(NotificationUpdateData $data, int $id): Notification
    {
        return DB::transaction(function () use ($data, $id): Notification {
            $item = $this->notificationRepository->findOrErrorForUpdate($id);
            $recipientChanged = $item[Notification::USER_TYPE] !== $data->user_type
                || $item[Notification::USER_ID] !== $data->user_id;

            $this->notificationRepository->update($item, [
                Notification::TITLE => $data->title,
                Notification::MESSAGE => $data->message,
                Notification::URL => $data->url,
                Notification::USER_TYPE => $data->user_type,
                Notification::USER_ID => $data->user_id,
                Notification::IS_READ => $recipientChanged ? 0 : $item[Notification::IS_READ],
            ]);

            return $item;
        });
    }
}
