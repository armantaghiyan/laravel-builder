<?php

namespace App\Core\Domain\Admin\Repositories;

use App\Core\Domain\Admin\Models\Admin;
use App\Http\Data\Admin\Admin\AdminIndexData;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Sanctum\PersonalAccessToken;

class AdminRepository {

	public function findById(int $id): Admin {
		return Admin::where('id', $id)->firstOrError();
	}

	public function index(AdminIndexData $data): array {
		$query = Admin::query()->with('roles');

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

	public function revokeTokens(Admin $admin): void {
		$admin->tokens()->delete();
	}

	public function activeSessions(Admin $admin): Collection {
		return $admin->tokens()
			->where(fn ($query) => $query->whereNull('expires_at')->orWhere('expires_at', '>', now()))
			->when(config('sanctum.expiration'), fn ($query, $expiration) => $query->where('created_at', '>', now()->subMinutes($expiration)))
			->orderByDesc('id')
			->get();
	}

	public function findSessionById(Admin $admin, int $id): PersonalAccessToken {
		return $admin->tokens()->where('id', $id)->firstOrFail();
	}

	public function revokeSession(PersonalAccessToken $session): void {
		$session->delete();
	}

	public function delete(Admin $admin): void {
		$admin->delete();
	}

	public function findByUsername(string $username): ?Admin {
		return Admin::where(Admin::USERNAME, $username)
			->first([Admin::ID, Admin::NAME, Admin::USERNAME, Admin::IMAGE, Admin::PASSWORD]);
	}
}
