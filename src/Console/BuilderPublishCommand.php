<?php

namespace Arman\LaravelBuilder\Console;

use Arman\LaravelBuilder\Http\Controllers\BuilderController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BuilderPublishCommand extends Command {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'builder:publish';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'builder publish command';


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


		$this->publishDir('Console', 'app/Console');
		$this->publishDir('Exceptions', 'app/Exceptions');
		$this->publishDir('Repositories', 'app/Repositories');
		$this->publishDir('Helpers', 'app/Helpers');
		$this->publishDir('Dto', 'app/Dto');
		$this->publishDir('Middleware', 'app/Http/Middleware');
		$this->publishDir('Controllers', 'app/Http/Controllers');
		$this->publishDir('Services', 'app/Services');
		$this->publishDir('lang', 'lang');
		$this->publishDir('database/migrations', 'database/migrations');
		$this->publishDir('Resources', 'app/Http/Resources');
		$this->publishDir('config', 'config');
		$this->publishDir('Models', 'app/Models');
		$this->publishDir('seeders', 'database/seeders');
		$this->publishDir('bootstrap', 'bootstrap');

		$this->publishFile('routes/admin.php', 'routes/admin.php');
		$this->publishFile('Providers/HelperServiceProvider.php', 'app/Providers/HelperServiceProvider.php');

		$this->info('Publishing configuration successfully.');
	}

	private function publishDir($from, $to): void {
		File::copyDirectory(__DIR__ . "/../../publish/$from", base_path($to));
	}
	private function publishFile($from, $to): void {
		File::copy(__DIR__ . "/../../publish/$from", base_path($to));
	}
}