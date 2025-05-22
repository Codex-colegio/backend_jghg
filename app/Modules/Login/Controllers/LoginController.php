<?php
namespace App\Modules\Login\Controllers;
use App\Core\Request;
use App\Core\Response;
use App\Modules\Login\Services\LoginService;
use App\Helpers\JwtHelper;


// Controlador público (sin token requerido)
class LoginController {
    private LoginService $service;
    private Response $response;

    public function __construct() {
        $this->service = new LoginService();
        $this->response = new Response();
    }

    public function login(Request $request): void {
        try {
            $body = $request->getRequestBody();
            $usuario = trim($body['usuario'] ?? '');
            $clave = $body['clave'] ?? '';

            $usuario_verificado = $this->service->obtenerPorUsuario($usuario);
            if (!$usuario_verificado || !password_verify($clave, $usuario_verificado['clave'])) {
                $this->response->sendError('Usuario o contraseña incorrecta', 401);
                return;
            }

            unset($usuario_verificado['clave']);

            $token = JwtHelper::generarToken([
                'id' => $usuario_verificado['id_usuario'],
                'usuario' => $usuario_verificado['login'],
                'cargo' => $usuario_verificado['cargo'],
                'permisos' => $usuario_verificado['permisos'] // ahora es array
            ]);

            $this->response->sendJson([
                'token' => $token,
                'usuario' => $usuario_verificado['login'],
                'cargo' => $usuario_verificado['cargo'],
                'permisos' => $usuario_verificado['permisos'],
                'estado' => $usuario_verificado['estado']
            ]);
        } catch (\Exception $e) {
            $this->response->sendError('Error al autenticar: ' . $e->getMessage(), 500);
        }
    }
}
