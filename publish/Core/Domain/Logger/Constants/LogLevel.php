<?php

namespace App\Core\Domain\Logger\Constants;

use App\Core\Domain\Common\Constants\EnumStructure;
use App\Core\Domain\Common\Traits\EnumOptions;

enum LogLevel: string implements EnumStructure {

    use EnumOptions;


    case Info = 'info';
    case Warning = 'warning';
    case Error = 'error';
    case Critical = 'critical';

    public function color(): string {
        return match ($this) {
            self::Info => 'info',
            self::Warning => 'warning',
            self::Error, self::Critical => 'danger',
        };
    }

    public function label(): string {
        return __("enum.log_levels.{$this->value}");
    }
}
