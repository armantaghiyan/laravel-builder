<?php

namespace App\Core\Domain\Admin\Repositories;

use App\Core\Domain\Admin\Models\Admin;
use App\Http\Constants\Permissions;
use App\Http\Data\Admin\Access\AccessAdminIndexData;
use App\Http\Data\Admin\Admin\AdminIndexData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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

	public function getRoleAdmins(Role $role, AccessAdminIndexData $data): array {
		$query = Admin::role($role);
		$count = $query->count();
		$items = $query->orderByDesc(Admin::ID)->page2($data->page_rows)
			->get([Admin::ID, Admin::NAME, Admin::USERNAME]);

		return [$items, $count];
	}

	public function getPermissionAdmins(Permission $permission, AccessAdminIndexData $data): array {
		$query = Admin::permission([$permission, Permissions::ADMIN_SUPER_ADMIN]);
		$count = $query->count();
		$items = $query->orderByDesc(Admin::ID)->page2($data->page_rows)
			->get([Admin::ID, Admin::NAME, Admin::USERNAME]);

		return [$items, $count];
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

	public function createSession(Admin $admin, int $maxSessions): NewAccessToken {
		return DB::transaction(function () use ($admin, $maxSessions): NewAccessToken {
			// Serialize logins for this admin so simultaneous requests also respect the limit.
			Admin::whereKey($admin->getKey())->lockForUpdate()->firstOrFail();

			$token = $admin->createToken('ADMIN TOKEN', ['*'], now()->addWeek());
			$oldSessionIds = $this->activeSessions($admin)->slice($maxSessions)->modelKeys();

			$admin->tokens()->whereKey($oldSessionIds)->delete();

			return $token;
		});
	}

	public function activeSessions(Admin $admin): Collection {
		return $admin->tokens()
			->where(fn($query) => $query->whereNull('expires_at')->orWhere('expires_at', '>', now()))
			->when(config('sanctum.expiration'), fn($query, $expiration) => $query->where('created_at', '>', now()->subMinutes($expiration)))
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
