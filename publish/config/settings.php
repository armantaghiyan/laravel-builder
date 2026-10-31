<?php

use App\Core\Domain\Setting\Constants\SettingKey;

return [
    SettingKey::ABOUT_US => [
        'group' => 'general',
        'type' => 'textarea',
        'default' => '',
        'rules' => ['present', 'nullable', 'string', 'max:10000'],
    ],
    SettingKey::MAX_SESSIONS => [
        'group' => 'security',
        'type' => 'integer',
        'default' => 1,
        'rules' => ['required', 'integer', 'min:1', 'max:100'],
    ],
];
