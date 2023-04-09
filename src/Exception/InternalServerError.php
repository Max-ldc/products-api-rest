<?php

namespace App\Exception;

use App\Http\ResponseCode;
use Exception;

class InternalServerError extends Exception
{
    public function __construct(string $msg = "")
    {
        $this->code = ResponseCode::INTERNAL_SERVER_ERROR;
        $this->message = $msg;
    }
}