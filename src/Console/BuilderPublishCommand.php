<?php

namespace Arman\LaravelBuilder\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
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

		$this->publishDir('Services', 'app/Services');
		$this->publishDir('Exceptions', 'app/Http/Exceptions');
		$this->publishDir('Console', 'app/Console');
		$this->publishDir('Middleware', 'app/Http/Middleware');
		$this->publishDir('lang', 'lang');
		$this->publishDir('database/migrations', 'database/migrations');
		$this->publishDir('seeders', 'database/seeders');
		$this->publishDir('bootstrap', 'bootstrap');
		$this->publishDir('routes', 'routes');
		Artisan::call('lang:publish');
		$this->publishConfig();

		$this->info('Publishing configuration successfully.');
	}

	private function publishDir($from, $to): void {
		File::copyDirectory(__DIR__ . "/../../publish/$from", base_path($to));
	}

	private function publishFile($from, $to): void {
		File::copy(__DIR__ . "/../../publish/$from", base_path($to));
	}

	private function publishConfig(): void {
		// add cors config
		Artisan::call('config:publish cors');

		$content = file_get_contents(base_path('config/cors.php'));
		if (!str_contains($content, 'admin/*')) {
			$content = str_replace('api/*', "api/*', 'admin/*", $content);
			file_put_contents(base_path('config/cors.php'), $content);
		}


		// add auth config
		$content = file_get_contents(base_path('config/auth.php'));
		if (!str_contains($content, 'admin')) {
			$content = str_replace("'guards' => [", "'guards' => [ 'admin' => ['driver' => 'sanctum', 'provider' => 'admins',], 'api' => ['driver' => 'sanctum', 'provider' => 'users',],", $content);
			$content = str_replace("'providers' => [", "'providers' => ['admins' => ['driver' => 'eloquent', 'model' => \App\Services\Domain\User\Admin\Models\Admin::class,],", $content);

			file_put_contents(base_path('config/auth.php'), $content);
		}
	}
}