<?php

namespace App\Core\Domain\Logger\Constants;

use App\Core\Domain\Common\Constants\EnumStructure;
use App\Core\Domain\Common\Traits\EnumOptions;

enum LogEvent: string implements EnumStructure {

	use EnumOptions;

	case AdminPasswordChanged = 'admin_password_changed';
	case AdminDeleted = 'admin_deleted';
	case AdminLoggedIn = 'admin_logged_in';
	case AdminLoggedOut = 'admin_logged_out';
	case AdminCreated = 'admin_created';
	case AdminUpdated = 'admin_updated';
	case AccessDeleted = 'access_deleted';
	case AccessCreated = 'access_created';
	case AccessUpdated = 'access_updated';
	case AdminRoleStatusChanged = 'admin_role_status_changed';
	case PermissionStatusChanged = 'permission_status_changed';

	public function color(): string {
		return '';
	}

	public function label(): string {
		return __("enum.log_event.{$this->value}");
	}
}
