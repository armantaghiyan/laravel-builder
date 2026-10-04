<?php

namespace App\Core\Application\Actions\Notification;

use App\Core\Domain\Notification\Models\Notification;
use App\Core\Domain\Notification\Repositories\NotificationRepository;
use App\Http\Data\Admin\Notification\NotificationStoreData;

readonly class NotificationStoreAction
{
    public function __construct(
        private NotificationRepository $notificationRepository,
    ) {}

    public function execute(NotificationStoreData $data): Notification
    {
        return $this->notificationRepository->create([
            Notification::TITLE => $data->title,
            Notification::MESSAGE => $data->message,
            Notification::URL => $data->url,
            Notification::USER_TYPE => $data->user_type,
            Notification::USER_ID => $data->user_id,
            Notification::IS_READ => 0,
        ]);
    }
}
