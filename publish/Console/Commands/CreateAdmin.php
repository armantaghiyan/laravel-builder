<?php

namespace App\Console\Commands;

use App\Dto\User\Admin\AdminStoreData;
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
		AdminService $adminService
	) {
		$name = $this->ask('Please enter your name');
		$username = $this->ask('Please enter the username');
		$password = $this->secret('Please enter the password');


		$adminService->store(new AdminStoreData($name, $username, $password));
	}
}
