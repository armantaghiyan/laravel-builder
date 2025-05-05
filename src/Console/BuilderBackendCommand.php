<?php

namespace Arman\LaravelBuilder\Console;

use Arman\LaravelBuilder\Http\Controllers\BuilderController;
use Illuminate\Console\Command;

class BuilderBackendCommand extends Command {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'builder:backend';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'builder command';

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function handle(): void {
		if (config('app.env') !== 'local') {
			$this->error('Please run this command in local environment.');
			return;
		}

		$builderController = new BuilderController();

		$tables = $builderController->getTables();

		$table = $this->choice('please select the table?', $tables);

		$this->info('select the table: ' . $table);

		$builderController->generateCode($table);

		$this->info('Builder generated!');
	}
}