<?php

namespace App\Core\Domain\Admin\Repositories;

use App\Core\Domain\Admin\Models\Faq;
use App\Http\Data\Admin\Admin\AdminIndexData;

class AdminRepository {

    public function findById(int $id): Faq {
        return Faq::where('id', $id)->firstOrError();
    }

    public function index(AdminIndexData $data): array {
        $query = Faq::query();

        $query->filter(Faq::ID, $data->id);
        $query->filter(Faq::NAME, "%{$data->name}%");
        $query->filter(Faq::USERNAME, "%{$data->username}%");
        $query->search([Faq::ID, Faq::NAME, Faq::USERNAME], $data->search);

        $count = $query->count();
        $items = $query->orderBy($data->sort, $data->sort_type)->page2($data->page_rows)->get();

        return [$items, $count];
    }

    public function create(array $data): Faq {
        return Faq::create($data);
    }

    public function update(Faq $admin, array $data): Faq {
        $admin->update($data);
        return $admin;
    }

    public function updateField($admin, array $fields): Faq {
        return $this->update($admin, $fields);
    }

    public function delete(Faq $admin): void {
        $admin->delete();
    }

    public function findByUsername(string $username): ?Faq {
        return Faq::where(Faq::USERNAME, $username)
            ->first([Faq::ID, Faq::NAME, Faq::USERNAME, Faq::IMAGE, Faq::PASSWORD]);
    }
}
