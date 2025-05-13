<?php
namespace App\Modules\Usuarios\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Modules\Usuarios\Services\UsuarioService;
use App\Helpers\JwtHelper;
use App\Middleware\AuthMiddleware;

class UsuarioController {
    private $service;
    private $response;

    public function __construct() {
        $this->service = new UsuarioService();
        $this->response = new Response();
    }

    /**
     * Obtiene todos los usuarios
     */
    public function index(Request $request): void {
        $payload = AuthMiddleware::verificarToken();

        if (!$payload) {
            $this->response->sendError('Acceso no autorizado: token inválido o ausente', 401);
            return;
        }
        try {
            $propiedades = $this->service->obtenerTodas();
            $this->response->sendJson($propiedades);
        } catch (\Exception $e) {
            $this->response->sendError('Error al obtener todos los usuarios: ' . $e->getMessage(), 500);
        }
    }
    

}
