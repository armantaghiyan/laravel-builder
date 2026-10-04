<?php

namespace App\Core\Domain\Notification\Repositories;

use App\Core\Domain\Common\Repositories\BaseRepository;
use App\Core\Domain\Notification\Models\Notification;
use App\Http\Data\Admin\Notification\NotificationIndexData;
use App\Http\Data\Notification\NotificationInboxData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class NotificationRepository extends BaseRepository
{
    public function model(): string
    {
        return Notification::class;
    }

    public function index(NotificationIndexData $data): array
    {
        $query = Notification::filter(Notification::ID, $data->id)
            ->filter(Notification::TITLE, $data->title ? '%'.$data->title.'%' : null)
            ->filter(Notification::USER_TYPE, $data->user_type)
            ->filter(Notification::USER_ID, $data->user_id)
            ->search([Notification::ID, Notification::TITLE, Notification::MESSAGE], $data->search);

        if ($data->is_global !== null) {
            if ($data->is_global) {
                $query->whereNull(Notification::USER_TYPE)->whereNull(Notification::USER_ID);
            } else {
                $query->whereNotNull(Notification::USER_TYPE)->whereNotNull(Notification::USER_ID);
            }
        }

        $count = $query->count();
        $items = $query->with('user')
            ->orderBy($data->sort ?? Notification::ID, $data->sort_type ?? 'desc')
            ->page2($data->page_rows ?? 7)->get();

        return [$items, $count];
    }

    public function show(int $id): Notification
    {
        return Notification::with('user')->where(Notification::ID, $id)->firstOrError();
    }

    public function inbox(NotificationInboxData $data, Model $user): array
    {
        $query = $this->visibleTo($user);
        $unreadCount = (clone $query)->where(Notification::IS_READ, 0)->count();

        $query->filter(Notification::IS_READ, $data->is_read);

        $count = $query->count();
        $items = $query->orderByDesc(Notification::ID)->page2($data->page_rows)->get();

        return [$items, $count, $unreadCount];
    }

    public function findVisibleOrError(int $id, Model $user): Notification
    {
        return $this->visibleTo($user)->where(Notification::ID, $id)->lockForUpdate()->firstOrError();
    }

    public function findOrErrorForUpdate(int $id): Notification
    {
        return Notification::where(Notification::ID, $id)->lockForUpdate()->firstOrError();
    }

    private function visibleTo(Model $user): Builder
    {
        return Notification::where(function (Builder $query) use ($user): void {
            $query->where(function (Builder $global): void {
                $global->whereNull(Notification::USER_TYPE)->whereNull(Notification::USER_ID);
            })->orWhere(function (Builder $personal) use ($user): void {
                $personal->where(Notification::USER_TYPE, $user->getMorphClass())
                    ->where(Notification::USER_ID, $user->getKey());
            });
        });
    }
}
