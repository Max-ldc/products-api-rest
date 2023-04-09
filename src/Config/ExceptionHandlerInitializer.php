<?php

namespace App\Config;

use App\Http\ResponseCode;
use Throwable;

class ExceptionHandlerInitializer
{
    public static function registerGlobalExceptionHandler()
    {
        set_exception_handler(function (Throwable $e) {     // gestion d'erreur généralisée
            $code = $e->getCode();
            if ($code === 0) {
                http_response_code(ResponseCode::INTERNAL_SERVER_ERROR);
                exit;
            } else {
                http_response_code($code);
                echo json_encode([
                    'error' => 'Une erreur est survenue',
                    'code' => $code,
                    'message' => $e->getMessage()
                ]);
            }
        });
    }
}
