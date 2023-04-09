<?php

namespace App\Exception;

use App\Http\ResponseCode;
use Exception;

class BadRequest extends Exception
{
    public function __construct(string $msg = "")
    {
        $this->code = ResponseCode::BAD_REQUEST;
        $this->message = $msg;
    }
}