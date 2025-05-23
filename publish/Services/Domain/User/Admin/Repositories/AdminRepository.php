<?php

namespace App\Services\Domain\User\Admin\Repositories;

use App\Services\Domain\User\Admin\Dto\AdminIndexData;
use App\Services\Domain\User\Admin\Models\Admin;

class AdminRepository {

    public function findById(int $id): Admin {
        return Admin::where('id', $id)->firstOrError();
    }

    public function index(AdminIndexData $data): array {
        $query = Admin::query();

        $query->filter(Admin::ID, $data->id);
        $query->filter(Admin::NAME, "%{$data->name}%");
        $query->filter(Admin::USERNAME, "%{$data->username}%");
        $query->search([Admin::ID, Admin::NAME, Admin::USERNAME], $data->search);

        $count = $query->count();
        $items = $query->orderBy($data->sort, $data->sort_type)->page2($data->page_rows)->get();

        return [$items, $count];
    }

    public function create(array $data): Admin {
        return Admin::create($data);
    }

    public function update(Admin $admin, array $data): Admin {
        $admin->update($data);
        return $admin;
    }

    public function updateField($admin, array $fields): Admin {
        return $this->update($admin, $fields);
    }

    public function delete(Admin $admin): void {
        $admin->delete();
    }

    public function findByUsername(string $username): ?Admin {
        return Admin::where(Admin::USERNAME, $username)
            ->first([Admin::ID, Admin::NAME, Admin::USERNAME, Admin::IMAGE, Admin::PASSWORD]);
    }
}
