<?php
namespace App\Core;

use App\Middleware\AuthMiddleware;

class BaseAuthController {
    protected $response;
    protected $payload;

    public function __construct() {
        $this->response = new Response();
        $this->payload = AuthMiddleware::verificarToken();

        if (!$this->payload) {
            $this->response->sendError('Acceso no autorizado: token inválido o ausente', 401);
            exit(); // Detiene la ejecución del controlador
        }
    }
}
