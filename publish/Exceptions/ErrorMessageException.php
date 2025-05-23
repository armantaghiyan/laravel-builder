<?php

namespace App\Exceptions;

use App\Services\Domain\Common\Constants\StatusCodes;
use Illuminate\Http\JsonResponse;

class ErrorMessageException extends \Exception {

	public function __construct(
		public $message,
		public $status = StatusCodes::Bad_request
	) {
		parent::__construct($message, $status);
	}

	public function render(): JsonResponse {
		return response()->json([
			'result' => 'error_message',
			'message' => $this->getMessage(),
		], $this->status);
	}
}
