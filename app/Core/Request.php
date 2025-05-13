<?php
namespace App\Core;

class Request {
    /**
     * Parámetros capturados de la ruta dinámica
     */
    private $routeParams = [];

    /**
     * Obtiene el método HTTP de la solicitud (GET, POST, etc.)
     */
    public function getHttpMethod(): string {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Obtiene la ruta solicitada (sin parámetros GET)
     * Ej: Para "/grupos?id=5" devuelve "/grupos"
     */
    public function getRequestPath(): string {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    /**
     * Obtiene el cuerpo de la solicitud decodificado como array
     * - Funciona con JSON, form-data y x-www-form-urlencoded
     */
    public function getRequestBody(): array {
        if (!empty($_POST)) {
            return $_POST;
        }

        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        $rawInput = file_get_contents('php://input');

        if (strpos($contentType, 'application/json') !== false) {
            return json_decode($rawInput, true) ?? [];
        }

        parse_str($rawInput, $parsedData);
        return $parsedData;
    }

    /**
     * Obtiene todos los parámetros GET
     */
    public function getQueryParams(): array {
        return $_GET;
    }

    /**
     * Establece los parámetros de ruta capturados
     * @param array $params Parámetros de ruta
     */
    public function setRouteParams(array $params): void {
        $this->routeParams = $params;
    }

    /**
     * Obtiene un parámetro específico de ruta
     * @param string $key Nombre del parámetro
     * @param mixed $default Valor por defecto si no existe
     */
    public function getRouteParam(string $key, $default = null) {
        return $this->routeParams[$key] ?? $default;
    }

    /**
     * Obtiene un parámetro específico de la URL o cuerpo
     * @param string $key Nombre del parámetro
     * @param mixed $default Valor por defecto si no existe
     */
    public function getParam(string $key, $default = null) {
        // Primero buscar en parámetros de ruta
        if (isset($this->routeParams[$key])) {
            return $this->routeParams[$key];
        }
        
        // Si no se encuentra, buscar en $_REQUEST (GET y POST)
        return $_REQUEST[$key] ?? $default;
    }
}