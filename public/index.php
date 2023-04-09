<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\DbInitializer;
use App\Config\ExceptionHandlerInitializer;
use App\Controller\ProductsApiCrudController;
use App\Http\ResponseCode;
use Symfony\Component\Dotenv\Dotenv;

header('Content-type: application/json; charset=UTF-8');

set_error_handler(function () {
    http_response_code(ResponseCode::INTERNAL_SERVER_ERROR);
    echo json_encode([
        'error' => 'Une erreur est survenue'
    ]);
});

$dotenv = new Dotenv();
$dotenv->loadEnv('.env');

ExceptionHandlerInitializer::registerGlobalExceptionHandler();
$pdo = DbInitializer::getPdoInstance();

$uri = $_SERVER['REQUEST_URI'];
$httpMethod = $_SERVER['REQUEST_METHOD'];

if (str_contains($uri, "/products")) {
    $controller = new ProductsApiCrudController($pdo, $uri, $httpMethod);
    $controller->handle();
}
