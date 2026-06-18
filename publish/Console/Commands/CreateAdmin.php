<?php

namespace App\Console\Commands;

use App\Core\Domain\Access\Services\AccessService;
use App\Core\Domain\Admin\Models\Faq;
use App\Core\Domain\Admin\Services\AdminService;
use App\Http\Data\Admin\Admin\AdminStoreData;
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

		$accessService->toggleAdminRole($admin[Faq::ID], 1);
	}
}
