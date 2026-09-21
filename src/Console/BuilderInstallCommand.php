<?php

namespace Arman\LaravelBuilder\Console;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Helper\ProgressBar;

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
	 * @var ProgressBar
	 */
	protected ProgressBar $progressBar;

	/**
	 * Execute the console command.
	 */
	public function handle(): void {
		if (config('app.env') !== 'local') {
			$this->error('❌ Please run this command in the local environment.');
			return;
		}

		$this->newLine();
		$this->line('<fg=cyan;options=bold>🚀 Starting Laravel Builder installation...</>');
		$this->newLine();

		$this->progressBar = $this->output->createProgressBar(9);
		$this->progressBar->setFormat(" %current%/%max% [%bar%] %percent:3s%%\n %message%\n");
		$this->progressBar->setMessage('Preparing...');
		$this->progressBar->start();

		//==============================================================================================================
		$this->section('📦 Step 1/9: Installing hekmatinasser/verta');

		$process = Process::timeout(600)->run('composer require hekmatinasser/verta');
		if ($process->successful()) {
			$this->info('✅ Package "hekmatinasser/verta" installed successfully.');
			$this->line($process->output());
		} else {
			$this->error('❌ Failed to install "hekmatinasser/verta".');
			$this->line($process->errorOutput());
		}

		$this->info('📤 Publishing verta config...');
		Artisan::call('vendor:publish', [
			'--provider' => 'Hekmatinasser\Verta\VertaServiceProvider',
		]);
		$this->line(Artisan::output());
		$this->info('✅ Verta config published.');
		$this->advance('Verta installed');

		//==============================================================================================================
		$this->section('📦 Step 2/9: Installing spatie/laravel-permission');

		$process = Process::timeout(600)->run('composer require spatie/laravel-permission');

		if ($process->successful()) {
			$this->info('✅ Package "spatie/laravel-permission" installed successfully.');
			$this->line($process->output());
		} else {
			$this->error('❌ Failed to install "spatie/laravel-permission".');
			$this->line($process->errorOutput());
		}

		$this->waitFor(10, 'Waiting before publishing Spatie Permission config');

		$this->info('📤 Publishing Spatie Permission config...');
		Artisan::call('vendor:publish', [
			'--provider' => 'Spatie\Permission\PermissionServiceProvider',
		]);
		$this->line(Artisan::output());
		$this->info('✅ Spatie Permission config published.');
		$this->advance('Spatie Permission installed');

		//==============================================================================================================
		$this->section('📦 Step 3/9: Installing spatie/laravel-data');

		$process = Process::timeout(600)->run('composer require spatie/laravel-data');

		if ($process->successful()) {
			$this->info('✅ Package "spatie/laravel-data" installed successfully.');
			$this->line($process->output());
		} else {
			$this->error('❌ Failed to install "spatie/laravel-data".');
			$this->line($process->errorOutput());
		}

		$this->waitFor(10, 'Waiting before publishing Spatie Laravel Data config');

		$this->info('📤 Publishing Spatie Laravel Data config...');
		Artisan::call('vendor:publish', [
			'--provider' => 'Spatie\LaravelData\LaravelDataServiceProvider',
			'--tag' => 'data-config',
		]);
		$this->line(Artisan::output());
		$this->info('✅ Spatie Laravel Data config published.');

		$tableNames = config('permission.table_names');
		Schema::table($tableNames['permissions'], static function (Blueprint $table) {
			$table->unsignedInteger('order')->after('guard_name')->default(0);
		});

		$this->advance('Spatie Laravel Data installed');

		//==============================================================================================================
		$this->section('🔐 Step 4/9: Installing Laravel API scaffolding');

		Artisan::call('install:api --force -n');
		$this->line(Artisan::output());
		$this->info('✅ Laravel API scaffolding installed.');
		$this->advance('API scaffolding installed');

		//==============================================================================================================
		$this->section('📄 Step 5/9: Publishing Builder files');

		Artisan::call('builder:publish');
		$this->line(Artisan::output());
		$this->info('✅ Builder files published.');
		$this->advance('Builder files published');

		//==============================================================================================================
		$this->section('🗄️  Step 6/9: Running database migrations');

		Artisan::call('migrate');
		$this->line(Artisan::output());
		$this->info('✅ Migrations completed.');
		$this->advance('Migrations completed');

		//==============================================================================================================
		$this->section('🌱 Step 7/9: Seeding access data');

		Artisan::call('db:seed --class=AccessSeeder');
		$this->line(Artisan::output());
		$this->info('✅ AccessSeeder executed.');
		$this->advance('Database seeded');

		//==============================================================================================================
		$this->section('📦 Step 8/9: Installing npm dependencies');

		$result = Process::timeout(600)->run('npm install');
		$this->line($result->output());

		if ($result->failed()) {
			$this->error('❌ "npm install" failed.');
			$this->line($result->errorOutput());
			$this->progressBar->finish();
			$this->newLine(2);
			return;
		}

		$this->info('✅ npm dependencies installed.');
		$this->advance('npm dependencies installed');

		//==============================================================================================================
		$this->section('🛠️  Step 9/9: Building frontend assets');

		$result = Process::run('npm run build');
		$this->line($result->output());

		if ($result->failed()) {
			$this->error('❌ "npm run build" failed.');
			$this->line($result->errorOutput());
			$this->progressBar->finish();
			$this->newLine(2);
			return;
		}

		$this->info('✅ Frontend assets built successfully.');
		$this->advance('Frontend assets built');

		//==============================================================================================================
		$this->progressBar->finish();
		$this->newLine(2);
		$this->line('<fg=green;options=bold>🎉 Installation completed successfully!</>');
		$this->newLine();
	}

	/**
	 * Print a formatted section header to keep the log readable.
	 */
	protected function section(string $title): void {
		$this->newLine();
		$this->line("<fg=yellow;options=bold>➡️  {$title}</>");
		$this->line(str_repeat('-', 60));
	}

	/**
	 * Sleep for the given number of seconds while informing the user why.
	 */
	protected function waitFor(int $seconds, string $reason): void {
		$this->comment("⏳ {$reason} ({$seconds}s)...");
		sleep($seconds);
	}

	/**
	 * Advance the shared progress bar and update its message.
	 */
	protected function advance(string $message): void {
		$this->progressBar->setMessage($message);
		$this->progressBar->advance();
		$this->newLine(2);
	}
}