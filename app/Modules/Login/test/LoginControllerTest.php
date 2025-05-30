<?php
require_once dirname(__DIR__, 4) . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../../');
$dotenv->load();

use PHPUnit\Framework\TestCase;
use App\Modules\Login\Controllers\LoginController;
use App\Core\Request;
use App\Core\Response;
use App\Modules\Login\Services\LoginService;
use App\Helpers\JwtHelper;

class LoginControllerTest extends TestCase
{
    public function testLoginExitoso()
    {
        // Mock del cuerpo de la solicitud
        $mockBody = [
            'usuario' => 'admin',
            'clave' => '1234'
        ];

        // Usuario simulado en la base de datos
        $usuarioEnDB = [
            'id_usuario' => 1,
            'login' => 'admin',
            'clave' => password_hash('1234', PASSWORD_DEFAULT),
            'cargo' => 'Administrador',
            'permisos' => ['ver_dashboard'],
            'estado' => 'activo',
            // **Estos dos faltaban** ↓↓↓
            'nom_usuario'=> 'Juan Pérez',
            'imagen'     => '/path/to/avatar.png',
        ];

        // Mocks de dependencias
        $requestMock = $this->createMock(Request::class);
        $requestMock->method('getRequestBody')->willReturn($mockBody);

        $serviceMock = $this->createMock(LoginService::class);
        $serviceMock->method('obtenerPorUsuario')->with('admin')->willReturn($usuarioEnDB);

        $responseMock = $this->getMockBuilder(Response::class)
            ->onlyMethods(['sendJson', 'sendError'])
            ->getMock();

        // Esperamos que sendJson sea llamado una vez con ciertos datos
        $responseMock->expects($this->once())
            ->method('sendJson')
            ->with($this->callback(function ($data) use ($usuarioEnDB) {
                return isset($data['token']) &&
                       $data['usuario'] === 'admin' &&
                       $data['cargo'] === 'Administrador' &&
                       $data['estado'] === 'activo';
            }));

        // Inyectar dependencias con Reflection para poder usar nuestros mocks
        $controller = new LoginController();

        $ref = new ReflectionClass($controller);
        $refService = $ref->getProperty('service');
        $refService->setAccessible(true);
        $refService->setValue($controller, $serviceMock);

        $refResponse = $ref->getProperty('response');
        $refResponse->setAccessible(true);
        $refResponse->setValue($controller, $responseMock);

        // Ejecutar
        $controller->login($requestMock);
    }

    // public function testLoginFallido()
    // {
    //     $mockBody = [
    //         'usuario' => 'admin',
    //         'clave' => 'clave_incorrecta'
    //     ];

    //     $usuarioEnDB = [
    //         'id_usuario' => 1,
    //         'login' => 'admin',
    //         'clave' => password_hash('123456', PASSWORD_DEFAULT), // clave real
    //         'cargo' => 'Administrador',
    //         'permisos' => ['ver_dashboard'],
    //         'estado' => 'activo'
    //     ];

    //     $requestMock = $this->createMock(Request::class);
    //     $requestMock->method('getRequestBody')->willReturn($mockBody);

    //     $serviceMock = $this->createMock(LoginService::class);
    //     $serviceMock->method('obtenerPorUsuario')->with('admin')->willReturn($usuarioEnDB);

    //     $responseMock = $this->getMockBuilder(Response::class)
    //         ->onlyMethods(['sendJson', 'sendError'])
    //         ->getMock();

    //     $responseMock->expects($this->once())
    //         ->method('sendError')
    //         ->with('Usuario o contraseña incorrecta', 401);

    //     $controller = new LoginController();

    //     $ref = new ReflectionClass($controller);
    //     $ref->getProperty('service')->setAccessible(true);
    //     $ref->getProperty('service')->setValue($controller, $serviceMock);
    //     $ref->getProperty('response')->setAccessible(true);
    //     $ref->getProperty('response')->setValue($controller, $responseMock);

    //     $controller->login($requestMock);
    // }

}