<?php

namespace App\Core\Domain\Common\Traits;

trait EnumOptions {

    public static function options(): array {
        return array_map(
            fn(self $case) => [
                'label' => $case->label(),
                'value' => $case->value,
            ],
            self::cases()
        );
    }
}
