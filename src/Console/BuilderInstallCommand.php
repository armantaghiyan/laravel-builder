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

		$this->info('Installing hekmatinasser/verta...');

		$process = Process::timeout(600)->run('composer require hekmatinasser/verta');
		if ($process->successful()) {
			$this->info('✅ Package installed successfully.');
			$this->line($process->output());

		} else {
			$this->error('❌ Failed to install package.');
			$this->line($process->errorOutput());
		}

		$this->info('Publishing verta config...');
		Artisan::call('vendor:publish', [
			'--provider' => 'Hekmatinasser\Verta\VertaServiceProvider',
		]);
		$this->info(Artisan::output());
		//==============================================================================================================
		$this->info('Installing spatie/laravel-permission...');

		$process = Process::timeout(600)->run('composer require spatie/laravel-permission');

		if ($process->successful()) {
			$this->info('✅ Package installed successfully.');
			$this->line($process->output());

		} else {
			$this->error('❌ Failed to install package.');
			$this->line($process->errorOutput());
		}

		sleep(10);

		$this->info('Publishing Spatie data config...');
		Artisan::call('vendor:publish', [
			'--provider' => 'Spatie\Permission\PermissionServiceProvider',
		]);

		$this->info(Artisan::output());

		//==============================================================================================================
		$this->info('Installing spatie/laravel-data...');

		$process = Process::timeout(600)->run('composer require spatie/laravel-data');

		if ($process->successful()) {
			$this->info('✅ Package installed successfully.');
			$this->line($process->output());

		} else {
			$this->error('❌ Failed to install package.');
			$this->line($process->errorOutput());
		}

		sleep(10);

		$this->info('Publishing Spatie Laravel Data config...');
		Artisan::call('vendor:publish', [
			'--provider' => 'Spatie\LaravelData\LaravelDataServiceProvider',
			'--tag' => 'data-config',
		]);
		$this->info(Artisan::output());
		//==============================================================================================================
		$this->info('Installing api');

		Artisan::call('install:api --force -n');

		$this->info(Artisan::output());
		//==============================================================================================================
		$this->info('Published file');

		Artisan::call('builder:publish');

		$this->info(Artisan::output());
		//==============================================================================================================
		$this->info('Run migration');

		Artisan::call('migrate');

		$this->info(Artisan::output());
		//==============================================================================================================
		$this->info('Run seeder');

		Artisan::call('db:seed --class=AccessSeeder');

		$this->info(Artisan::output());

		//==============================================================================================================
		$this->info('Install npm dependencies');

		$result = Process::run('npm install');

		$this->line($result->output());

		if ($result->failed()) {
			$this->error($result->errorOutput());

			return;
		}

		$this->info('Npm build');

		$result = Process::run('npm run build');

		$this->line($result->output());

		if ($result->failed()) {
			$this->error($result->errorOutput());
			return;
		}

		$this->info('install success');
	}
}
