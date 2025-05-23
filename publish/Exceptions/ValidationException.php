<?php

namespace App\Exceptions;

use App\Services\Domain\Common\Constants\StatusCodes;
use Illuminate\Http\JsonResponse;

class ValidationException extends \Exception {

	public function __construct(
		public $message
	) {
		parent::__construct($message, StatusCodes::Bad_request);
	}

	public function render(): JsonResponse {

		return response()->json([
			'result' => 'error_validation',
			'errors' => json_decode($this->getMessage()),
		], StatusCodes::Bad_request);
	}
}
