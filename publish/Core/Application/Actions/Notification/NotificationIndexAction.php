<?php

namespace App\Core\Application\Actions\Notification;

use App\Core\Domain\Notification\Repositories\NotificationRepository;
use App\Http\Data\Admin\Notification\NotificationIndexData;

readonly class NotificationIndexAction
{
    public function __construct(
        private NotificationRepository $notificationRepository,
    ) {}

    public function execute(NotificationIndexData $data): array
    {
        return $this->notificationRepository->index($data);
    }
}
