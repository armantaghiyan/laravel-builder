<?php

namespace App\Repositories\User;


use App\Dto\User\Admin\AdminIndexData;
use App\Models\User\Admin;

class AdminRepository {

    public function findById($id): Admin {
        return Admin::where('id', $id)->firstOrError();
    }

    public function index(AdminIndexData $data): array {
        $query = Admin::query();

        $query->filter(Admin::ID, $data->id);
		$query->filter(Admin::NAME, "%".$data->name."%");
		$query->filter(Admin::USERNAME, "%".$data->username."%");
		$query->search([Admin::ID, Admin::NAME, Admin::USERNAME],$data->search);


        $count = $query->count();
        $items = $query->orderBy($data->sort, $data->sort_type)->page2($data->page_rows)->get();

        return [$items, $count];
    }

    public function create(array $data): Admin {
        return Admin::create($data);
    }

    public function update(Admin $item, array $data): Admin {
        $item->update($data);
        return $item;
    }

    public function delete(Admin $item): void {
        $item->delete();
    }
}
