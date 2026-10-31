<?php

namespace App\Core\Application\Actions\Setting;

use App\Core\Domain\Setting\Repositories\SettingRepository;

readonly class SettingIndexAction {

    public function __construct(
        private SettingRepository $settingRepository,
    ) {
    }

    public function execute(): array {
        $items = [];

        foreach (config('settings') as $key => $definition) {
            $items[] = [
                'key' => $key,
                'group' => $definition['group'],
                'type' => $definition['type'],
                'default' => $definition['default'],
                'value' => $this->settingRepository->get($key),
            ];
        }

        return $items;
    }
}
