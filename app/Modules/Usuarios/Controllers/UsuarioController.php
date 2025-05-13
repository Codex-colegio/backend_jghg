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
    

    /**
    * Obtiene un usuario por su ID
    */
    public function mostrar(Request $request): void {
        // Encabezados CORS
 
        try {
            $id = $request->getParam('id'); // Obtiene el ID de la URL
            if (!is_numeric($id)) {
                $this->response->sendError('ID de usuario inválido', 400);
                return;
            }

            $Usuario = $this->service->obtenerPorId($id);
            if (!$Usuario) {
                $this->response->sendError('Usuario no encontrado', 404);
                return;
            }

            $this->response->sendJson($Usuario);
        } catch (\Exception $e) {
            $this->response->sendError('Error al obtener el usuario: ' . $e->getMessage(), 500);
        }
    }

    public function login($request) {
        $data = $request->getRequestBody(); // o como estés manejando el request
    
        $usuario = $data['usuario'] ?? '';
        $clave = $data['clave'] ?? '';

        $usuario_v = $this->service->obtenerPorUsuario($usuario,$clave);
        // Aquí haces tu validación, por ejemplo contra una base de datos
        if (!$usuario_v) {
            (new \App\Core\Response())->sendJson([
                'error' => 'Credenciales inválidas'
            ], 401);
        } else {
            // Ejemplo: respuesta simulada
            (new \App\Core\Response())->sendJson([
                'usuario' => 'admin',
                'permisos' => ['escritura', 'lectura']
            ]);
        }
    }

    public function login2(Request $request): void {
        try {
            $body = $request->getRequestBody(); // Obtener todo el cuerpo como array

            $usuario = $body['usuario'] ?? '';
            $clave = $body['clave'] ?? '';

            $usuario_verificado = $this->service->obtenerPorUsuario($usuario);
            if (!$usuario_verificado) {
                $this->response->sendError('Usuario o contraseña incorrecta. Usuario: ' . $usuario, 404);
                return;
            }

            // Verifica el hash de la clave
            if (!password_verify($clave, $usuario_verificado['clave'])) {
                $this->response->sendError('Usuario o contraseña incorrecta2', 401);
                return;
            }
            
            // No devolver la clave hash en la respuesta
            unset($usuario_verificado['clave']);

            $this->response->sendJson($usuario_verificado);
        } catch (\Exception $e) {
            $this->response->sendError('Error al obtener el usuario: ' . $e->getMessage(), 500);
        }
    }

    public function login3(Request $request): void {
        try {
            $body = $request->getRequestBody(); // Obtener todo el cuerpo como array

            $usuario = trim($body['usuario'] ?? '');  // Eliminar espacios innecesarios
            $clave = $body['clave'] ?? '';

            $usuario_verificado = $this->service->obtenerPorUsuario($usuario);
            if (!$usuario_verificado) {
                $this->response->sendError('Usuario o contraseña incorrecta. Usuario: ' . $usuario, 404);
                return;
            }

            // Verifica el hash de la clave
            if (!password_verify($clave, $usuario_verificado['clave'])) {
                $this->response->sendError('Usuario o contraseña incorrecta', 401);
                return;
            }

            // No devolver la clave hash en la respuesta
            unset($usuario_verificado['clave']);

            // ==============================
            // Generar JWT usando JwtHelper
            // ==============================
            $token = JwtHelper::generarToken([
                'id' => $usuario_verificado['idUsuario'],
                'usuario' => $usuario_verificado
            ]);

            // Escapar los datos antes de enviarlos en la respuesta
            foreach ($usuario_verificado as $key => $value) {
                if (is_string($value)) {
                    $usuario_verificado[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                }
            }            
            // Respuesta con el token
            $this->response->sendJson([
                'token' => $token,
                'usuario' => $usuario_verificado
            ]);
        } catch (\Exception $e) {
            $this->response->sendError('Error al obtener el usuario: ' . $e->getMessage(), 500);
        }
    }
}
