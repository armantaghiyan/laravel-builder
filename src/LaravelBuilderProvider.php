<?php

namespace Arman\LaravelBuilder;

use Illuminate\Support\ServiceProvider;

class LaravelBuilderProvider extends ServiceProvider {

	/**
	 * Register any package services.
	 *
	 * @return void
	 */
	public function register(): void {
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot(): void {
		$this->registerCommands();
	}

	/**
	 * Register the package's commands.
	 *
	 * @return void
	 */
	protected function registerCommands(): void {
		if ($this->app->runningInConsole()) {
			$this->commands([
				Console\BuilderCommand::class,
			]);
		}
	}
}
