<?php
namespace App\Modules\Usuarios\Controllers;
use App\Core\BaseAuthController;
use App\Core\Request;
use App\Modules\Usuarios\Services\UsuarioService;

class UsuarioController extends BaseAuthController {
    private $service;
    

    public function __construct() {
        parent::__construct(); // Verifica token automáticamente
        $this->service = new UsuarioService();
    }

    public function index(Request $request): void {
        try {
            $usuarios = $this->service->obtenerTodas();
            $this->response->sendJson($usuarios);
        } catch (\Exception $e) {
            $this->response->sendError('Error al obtener los usuarios: ' . $e->getMessage(), 500);
        }
    }

    public function mostrar(Request $request): void {
        try {
            $id = $request->getParam('id');
            if (!is_numeric($id)) {
                $this->response->sendError('ID de usuario inválido', 400);
                return;
            }

            $usuario = $this->service->obtenerPorId($id);
            if (!$usuario) {
                $this->response->sendError('Usuario no encontrado', 404);
                return;
            }

            $this->response->sendJson($usuario);
        } catch (\Exception $e) {
            $this->response->sendError('Error al obtener el usuario: ' . $e->getMessage(), 500);
        }
    }
}
