<?php

namespace Arman\LaravelBuilder\Console;

use Exception;
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

		$this->publishDir('Core', 'app/Core');
		$this->publishDir('Http', 'app/Http');
		$this->publishDir('Console', 'app/Console');
		$this->publishDir('lang', 'lang');
		$this->publishDir('database/migrations', 'database/migrations');
		$this->publishDir('seeders', 'database/seeders');
		$this->publishDir('bootstrap', 'bootstrap');
		$this->publishDir('routes', 'routes');
		$this->publishDir('resources', 'resources');
		$this->publishDir('Providers', 'app/Providers');
		$this->publishFile('tsconfig.json', 'tsconfig.json');
		$this->publishFile('vite.config.js', 'vite.config.js');

		Artisan::call('lang:publish');
		$this->publishConfig();

		$this->addPackageDependency("@tailwindcss/vite", "^4.2.4", "devDependencies");
		$this->addPackageDependency("@types/node", "^25.6.0", "devDependencies");
		$this->addPackageDependency("@types/toastify-js", "^1.12.4", "devDependencies");
		$this->addPackageDependency("@vitejs/plugin-vue", "^6.0.6", "devDependencies");
		$this->addPackageDependency("concurrently", "^9.0.1", "devDependencies");
		$this->addPackageDependency("laravel-vite-plugin", "^2.1.0", "devDependencies");
		$this->addPackageDependency("tailwindcss", "^4.2.4", "devDependencies");
		$this->addPackageDependency("typescript", "^6.0.3", "devDependencies");
		$this->addPackageDependency("vite", "^7.3.2", "devDependencies");
		$this->addPackageDependency("vue-tsc", "^3.2.8", "devDependencies");
		$this->addPackageDependency( "axios", "^1.16.0", "devDependencies");
		$this->addPackageDependency( "@headlessui/vue", "^1.7.23");
		$this->addPackageDependency( "browser-image-compression", "^2.0.2");
		$this->addPackageDependency( "pinia", "^3.0.4");
		$this->addPackageDependency( "sweetalert2", "^11.26.24");
		$this->addPackageDependency( "tippy.js", "^6.3.7");
		$this->addPackageDependency( "toastify-js", "^1.12.0");
		$this->addPackageDependency( "vue", "^3.5.34");
		$this->addPackageDependency( "vue-router", "^5.0.6");
		$this->addPackageDependency( "unplugin-vue-components", "^32.1.0");
		$this->addPackageDependency( "unplugin-auto-import", "^21.0.0");
		$this->addPackageDependency( "vue-multiselect", "^3.5.0");
		$this->addPackageDependency( "vue3-persian-datetime-picker", "^1.2.2");

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
			$content = str_replace("'providers' => [", "'providers' => ['admins' => ['driver' => 'eloquent', 'model' => \App\Core\Domain\Admin\Models\Admin::class,],", $content);

			file_put_contents(base_path('config/auth.php'), $content);
		}
	}

	function addPackageDependency($name, $version, $type = 'dependencies') {
		$file = base_path('package.json');

		if (!file_exists($file)) {
			throw new Exception('package.json file not found!');
		}

		$content = json_decode(file_get_contents($file), true);

		if (json_last_error() !== JSON_ERROR_NONE) {
			throw new Exception('Invalid JSON in package.json');
		}

		if (!isset($content[$type])) {
			$content[$type] = [];
		}

		$content[$type][$name] = $version;

		file_put_contents($file, json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

		return true;
	}

}