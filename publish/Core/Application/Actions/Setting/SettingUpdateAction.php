<?php

namespace App\Core\Application\Actions\Setting;

use App\Core\Domain\Setting\Repositories\SettingRepository;
use App\Http\Data\Admin\Setting\SettingUpdateData;

readonly class SettingUpdateAction {

    public function __construct(
        private SettingRepository $settingRepository,
    ) {
    }

    public function execute(SettingUpdateData $data): void {
        $definition = config('settings')[$data->key];
        $value = $data->value ?? $definition['default'];
        $value = match ($definition['type']) {
            'integer' => (int)$value,
            'boolean' => (bool)$value,
            default => $value,
        };

        $this->settingRepository->set($data->key, $value);
    }
}
