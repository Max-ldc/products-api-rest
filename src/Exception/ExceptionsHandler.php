<?php

namespace App\Exception;

use Exception;

class ExceptionsHandler
{
    static function sendError(Exception $e): void
    {
        http_response_code($e->getCode());
        echo json_encode([
            'error' => 'Une erreur est survenue',
            'code' => $e->getCode(),
            'message' => $e->getMessage()
        ]);
    }
}