<?php

namespace App\Config;

use App\Http\ResponseCode;
use Throwable;

class ExceptionHandlerInitializer
{
    public static function registerGlobalExceptionHandler()
    {
        set_exception_handler(function (Throwable $e) {     // gestion d'erreur généralisée
            http_response_code(ResponseCode::INTERNAL_SERVER_ERROR);
            echo json_encode([
                'error' => 'An error occurred',
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]);
        });
    }
}
