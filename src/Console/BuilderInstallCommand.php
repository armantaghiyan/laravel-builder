<?php

namespace Arman\LaravelBuilder\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Process;

class BuilderInstallCommand extends Command {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'builder:install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'builder command';

	/**
	 * Execute the console command.
	 */
	public function handle(): void {
		if (config('app.env') !== 'local') {
			$this->error('Please run this command in local environment.');
			return;
		}

		$this->info('Installing spatie/laravel-permission...');

		$process = Process::timeout(600)->run('composer require spatie/laravel-permission');

		if ($process->successful()) {
			$this->info('✅ Package installed successfully.');
			$this->line($process->output());

			$this->info('Publishing Spatie permission config...');
			Artisan::call('vendor:publish', [
				'--provider' => 'Spatie\Permission\PermissionServiceProvider',
			]);
			$this->info(Artisan::output());
		} else {
			$this->error('❌ Failed to install package.');
			$this->line($process->errorOutput());
		}

		//==============================================================================================================
		$this->info('Installing spatie/laravel-data...');

		$process = Process::timeout(600)->run('composer require spatie/laravel-data');

		if ($process->successful()) {
			$this->info('✅ Package installed successfully.');
			$this->line($process->output());

			$this->info('Publishing Spatie Laravel Data config...');
			Artisan::call('vendor:publish', [
				'--provider' => 'Spatie\LaravelData\LaravelDataServiceProvider',
				'--tag' => 'data-config',
			]);
			$this->info(Artisan::output());
		} else {
			$this->error('❌ Failed to install package.');
			$this->line($process->errorOutput());
		}


	}
}
