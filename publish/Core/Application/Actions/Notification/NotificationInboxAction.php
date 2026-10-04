<?php

namespace App\Core\Application\Actions\Notification;

use App\Core\Domain\Notification\Repositories\NotificationRepository;
use App\Http\Data\Notification\NotificationInboxData;
use Illuminate\Database\Eloquent\Model;

readonly class NotificationInboxAction
{
    public function __construct(
        private NotificationRepository $notificationRepository,
    ) {}

    public function execute(NotificationInboxData $data, Model $user): array
    {
        return $this->notificationRepository->inbox($data, $user);
    }
}
