<?php

namespace App\Http\Exceptions;

use App\Core\Domain\Common\Constants\StatusCodes;
use Illuminate\Http\JsonResponse;

class RateLimitException extends \Exception {

    public function __construct() {
        parent::__construct('rate_limit', StatusCodes::Many_requests);
    }

    public function render(): JsonResponse {
        return response()->json([
            'result' => 'rate_limit',
        ], StatusCodes::Many_requests);
    }
}
