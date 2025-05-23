<?php

namespace App\Console\Commands;

use App\Dto\User\Admin\AdminStoreData;
use App\Models\User\Admin;
use App\Services\Domain\User\AccessService;
use App\Services\Domain\User\AdminService;
use Illuminate\Console\Command;

class CreateAdmin extends Command {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'helper:create-admin';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'create admin';


	/**
	 * Execute the console command.
	 */
	public function handle(
		AdminService $adminService,
		AccessService $accessService
	) {
		$name = $this->ask('Please enter your name');
		$username = $this->ask('Please enter the username');
		$password = $this->secret('Please enter the password');


		$admin = $adminService->store(new AdminStoreData($name, $username, $password));

		$accessService->toggleAdminRole($admin[Admin::ID], 1);
	}
}
