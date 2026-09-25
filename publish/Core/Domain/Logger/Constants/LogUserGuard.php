<?php

namespace App\Core\Domain\Logger\Constants;

use App\Core\Domain\Common\Constants\EnumStructure;
use App\Core\Domain\Common\Traits\EnumOptions;

enum LogUserGuard: string implements EnumStructure {

    use EnumOptions;

    case User = 'user';
    case Admin = 'admin';
    public function color(): string {
        return match ($this) {
            self::User => 'warning',
            self::Admin => 'danger',
        };
    }

    public function label(): string {
        return __("enum.log_user_guard.{$this->value}");
    }
}
