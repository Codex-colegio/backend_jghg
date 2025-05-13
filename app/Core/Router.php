<?php
namespace App\Core;

use App\Core\Request;
use App\Core\Response;

class Router {
    /**
     * Almacena las rutas registradas organizadas por método HTTP
     * @var array<string, array>
     */
    private $registeredRoutes = [
        'GET'    => [],
        'POST'   => [],
        'PUT'    => [],
        'DELETE' => []
    ];

    /**
     * Almacena los parámetros capturados de la URL
     * @var array
     */
    private $routeParams = [];

    /**
     * Registra una ruta GET
     */
    public function get(string $uriPattern, $action): void {
        $this->registerRoute('GET', $uriPattern, $action);
    }

    /**
     * Registra una ruta POST
     */
    public function post(string $uriPattern, $action): void {
        $this->registerRoute('POST', $uriPattern, $action);
    }

    /**
     * Registra una ruta PUT
     */
    public function put(string $uriPattern, $action): void {
        $this->registerRoute('PUT', $uriPattern, $action);
    }

    /**
     * Registra una ruta DELETE
     */
    public function delete(string $uriPattern, $action): void {
        $this->registerRoute('DELETE', $uriPattern, $action);
    }

    /**
     * Procesa la solicitud entrante y despacha la ruta correspondiente
     */
    public function dispatch(): void {
        $request = new Request();
        $httpMethod = $request->getHttpMethod();
        $requestUri = $request->getRequestPath();

        foreach ($this->registeredRoutes[$httpMethod] as $definedUri => $action) {
            if ($this->uriMatchesPattern($definedUri, $requestUri)) {
                // Pasar los parámetros de ruta al request
                $request->setRouteParams($this->routeParams);
                $this->executeRouteAction($action, $request);
                return;
            }
        }

        $this->sendNotFoundResponse();
    }

    /**
     * Compara la URI solicitada con un patrón registrado
     * - Soporta parámetros dinámicos con formato {parametro}
     * - Ej: '/usuarios/{id}' coincide con '/usuarios/42'
     */
    private function uriMatchesPattern(string $pattern, string $uri): bool {
        // Convertir patrones con {param} a regex
        $regexReadyPattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $pattern);
        $regex = "@^{$regexReadyPattern}$@";
        
        $matches = [];
        $result = preg_match($regex, $uri, $matches);
        
        if ($result) {
            // Limpiar valores numéricos en $matches y guardar solo los named params
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $this->routeParams[$key] = $value;
                }
            }
        }
        
        return (bool) $result;
    }

    /**
     * Ejecuta la acción asociada a la ruta
     * @param mixed $action Puede ser un closure o array [ClaseController, 'metodo']
     */
    private function executeRouteAction($action, Request $request): void {
        if (is_array($action)) {
            [$controllerClass, $methodName] = $action;
            $controller = new $controllerClass();
            $controller->$methodName($request);
        } else {
            call_user_func($action, $request);
        }
    }

    /**
     * Envía respuesta estándar para recursos no encontrados
     */
    private function sendNotFoundResponse(): void {
        (new Response())->sendJson([
            'error' => 'El recurso solicitado no existe',
            'documentation' => 'https://api.tudominio.com/docs'
        ], 404);
        //esto significa que no encontró ninguna ruta que coincida con la URL que estás pidiendo (/api/mueblesnsdi).
    }

    /**
     * Método interno para registrar rutas en la estructura
     */
    private function registerRoute(string $httpMethod, string $uriPattern, $action): void {
        $this->registeredRoutes[$httpMethod][$uriPattern] = $action;
    }
}