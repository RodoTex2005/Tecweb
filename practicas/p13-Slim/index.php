<?php

error_log("=== SLIM ACCESS ===");
error_log("Request URI: " . $_SERVER['REQUEST_URI']);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require 'vendor/autoload.php';

// Crear la aplicación Slim v4
$app = AppFactory::create();

// **CORREGIR: Configurar la ruta base correcta**
$app->setBasePath("/Tecweb/practicas/p13-Slim");

// Habilitar el parsing de body para POST/PUT
$app->addBodyParsingMiddleware();

// Ruta GET básica - Hola Mundo
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("¡Hola Mundo Slim!!");
    return $response;
});

// Ruta GET con parámetro
$app->get('/hola/{nombre}', function (Request $request, Response $response, $args) {
    $nombre = $args['nombre'];
    $response->getBody()->write("Hola, " . $nombre . "!");
    return $response;
});

// Ruta para probar POST
$app->post('/pruebapost', function (Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $response->getBody()->write("Método POST recibido. Datos: " . json_encode($data));
    return $response;
});

// Ruta para probar JSON
$app->post('/testjson', function (Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $responseData = [
        'status' => 'success',
        'message' => 'JSON recibido correctamente',
        'data' => $data
    ];
    $response->getBody()->write(json_encode($responseData));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
?>