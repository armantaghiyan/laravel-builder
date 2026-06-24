<?php

namespace App\Console\Commands;

use App\Core\Application\Actions\Access\AccessToggleAdminRoleAction;
use App\Core\Application\Actions\Admin\AdminStoreAction;
use App\Core\Domain\Admin\Models\Admin;
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
		AdminStoreAction            $adminStoreAction,
		AccessToggleAdminRoleAction $accessToggleAdminRoleAction,
	) {
		$name = $this->ask('Please enter your name');
		$username = $this->ask('Please enter the username');
		$password = $this->secret('Please enter the password');

		$admin = $adminStoreAction->execute(new AdminStoreData($name, $username, $password));

		$accessToggleAdminRoleAction->execute($admin[Admin::ID], 1);
	}
}
