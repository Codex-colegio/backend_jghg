<?php

// Cabeceras CORS - deben ir antes de cualquier salida
header("Access-Control-Allow-Origin: http://app.localhost");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Manejo de solicitudes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
use App\Core\App;
use App\Modules\Usuarios\Controllers\UsuarioController;

$app = new App();
$router = $app->getRouter();

// Propiedades
$router->post('/api/usuarios/login', [UsuarioController::class, 'login3']);
$router->get('/api/usuarios/{id}', [UsuarioController::class, 'mostrar']);
$router->get('/api/usuarios', [UsuarioController::class, 'index']);


