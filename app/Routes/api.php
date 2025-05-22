<?php
use App\Core\App;
use App\Modules\Usuarios\Controllers\UsuarioController;
use App\Modules\Login\Controllers\LoginController;
use App\Modules\Permisos\Controllers\PermisoController;


$app = new App();
$router = $app->getRouter();

// Propiedades
$router->post('/api/login', [LoginController::class, 'login']);
$router->get('/api/usuarios/{id}', [UsuarioController::class, 'mostrar']);
$router->get('/api/usuarios', [UsuarioController::class, 'index']);
$router->get('/api/permisos', [PermisoController::class, 'mostrar_permiso']);


