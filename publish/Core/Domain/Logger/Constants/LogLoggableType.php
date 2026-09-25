<?php

namespace App\Core\Domain\Logger\Constants;

use App\Core\Domain\Common\Constants\EnumStructure;
use App\Core\Domain\Common\Traits\EnumOptions;

enum LogLoggableType: string implements EnumStructure {

    use EnumOptions;

    case Admin = 'admin';
    public function color(): string {
        return '';
    }

    public function label(): string {
        return __("enum.log_loggable_type.{$this->value}");
    }
}
