<?php

namespace App\Core\Domain\Logger\Constants;

use App\Core\Domain\Common\Constants\EnumStructure;
use App\Core\Domain\Common\Traits\EnumOptions;

enum LogEvent: string implements EnumStructure {

    use EnumOptions;

    case TestIndex = 'test_store';
    public function color(): string {
        return '';
    }

    public function label(): string {
        return __("enum.log_event.{$this->value}");
    }
}
