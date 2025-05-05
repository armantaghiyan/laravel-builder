<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider {

    /**
     * Register services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {
        $this->loadHelpers();
    }

    private function loadHelpers() {
        $helperFiles = config('helper.files');
        if (gettype($helperFiles) === 'array') {
            foreach ($helperFiles as $file) {
                try {
                    require_once($file);
                } catch (\Throwable $exception) {
                    Log::error('fail load helper ' . $file . $exception->getMessage());
                }
            }
        }
    }
}
