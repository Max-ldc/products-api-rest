<?php

namespace App\Exception;

use App\Http\ResponseCode;
use Exception;

class UnprocessableContentException extends Exception
{
    public function __construct()
    {
        $this->code = ResponseCode::UNPROCESSABLE_CONTENT;
    }
}