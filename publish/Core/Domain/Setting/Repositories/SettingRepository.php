<?php

namespace App\Core\Domain\Setting\Repositories;

use App\Core\Domain\Setting\Models\Setting;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;
use RuntimeException;

class SettingRepository {

    public function get(string $key): string|int|bool|null {
        $definition = $this->definition($key);
        $cacheKey = $this->cacheKey($key);
        $cached = Cache::get($cacheKey);

        if ($cached !== null) {
            return $cached['value'];
        }

        // Readers and writers share the lock so a cache miss cannot restore an old value.
        return Cache::lock($cacheKey . ':lock', 10)->block(5, function () use ($key, $cacheKey, $definition) {
            $cached = Cache::rememberForever($cacheKey, function () use ($key, $definition): array {
                $setting = Setting::query()->find($key);

                return ['value' => $setting ? $setting[Setting::VALUE] : $definition['default']];
            });

            return $cached['value'];
        });
    }

    public function set(string $key, string|int|bool|null $value): void {
        $this->definition($key);
        $cacheKey = $this->cacheKey($key);

        Cache::lock($cacheKey . ':lock', 10)->block(5, function () use ($key, $value, $cacheKey): void {
            Cache::forget($cacheKey);

            Setting::query()->updateOrCreate([Setting::KEY => $key], [Setting::VALUE => $value]);

            if (!Cache::forever($cacheKey, ['value' => $value])) {
                throw new RuntimeException('Unable to cache the setting.');
            }
        });
    }

    private function definition(string $key): array {
        return config('settings')[$key] ?? throw new InvalidArgumentException("Unknown setting: {$key}");
    }

    private function cacheKey(string $key): string {
        return 'settings:' . $key;
    }
}
