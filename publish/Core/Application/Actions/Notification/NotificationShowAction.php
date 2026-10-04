<?php

namespace App\Core\Application\Actions\Notification;

use App\Core\Domain\Notification\Models\Notification;
use App\Core\Domain\Notification\Repositories\NotificationRepository;

readonly class NotificationShowAction
{
    public function __construct(
        private NotificationRepository $notificationRepository,
    ) {}

    public function execute(int $id): Notification
    {
        return $this->notificationRepository->show($id);
    }
}
