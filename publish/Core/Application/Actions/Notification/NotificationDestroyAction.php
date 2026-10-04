<?php

namespace App\Core\Application\Actions\Notification;

use App\Core\Domain\Notification\Repositories\NotificationRepository;

readonly class NotificationDestroyAction
{
    public function __construct(
        private NotificationRepository $notificationRepository,
    ) {}

    public function execute(int $id): void
    {
        $item = $this->notificationRepository->findOrErrorById($id);
        $this->notificationRepository->delete($item);
    }
}
