<?php
namespace App\Modules\Permisos\Controllers;
use App\Core\BaseAuthController; //Verificaicón de token
use App\Core\Request;
use App\Modules\Permisos\Services\PermisoService;

class PermisoController extends BaseAuthController {
    private $service;
    

    public function __construct() {
        parent::__construct(); // Verifica token automáticamente
        $this->service = new PermisoService();
    }


    public function mostrar_todo(Request $request): void {
        try {
            $usuarios = $this->service->obtenerTodas();
            $this->response->sendJson($usuarios);
        } catch (\Exception $e) {
            $this->response->sendError('Error al obtener los usuarios: ' . $e->getMessage(), 500);
        }
    }

    public function mostrar_permiso(Request $request): void {
        try {
            $id = $request->getParam('id');
            if (!is_numeric($id)) {
                $this->response->sendError('ID de usuario inválido', 400);
                return;
            }

            $usuarios_permiso = $this->service->obtenerPorId($id);
            if (!$usuarios_permiso) {
                $this->response->sendError('Permisos de usuario no encontrado', 404);
                return;
            }
            unset($usuarios_permiso['id_permiso']);
            unset($usuarios_permiso['nom_permiso']);

            $this->response->sendJson($usuarios_permiso);
        } catch (\Exception $e) {
            $this->response->sendError('Error al obtener los permisos del usuario: ' . $e->getMessage(), 500);
        }
    }
   
}
