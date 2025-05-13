<?php
namespace App\Core;

class Response {
    /**
     * Envía una respuesta JSON estandarizada
     * @param mixed $data Datos a enviar
     * @param int $statusCode Código HTTP de respuesta
     * @param array $headers Encabezados adicionales
     */
    public function sendJson($data, int $statusCode = 200, array $headers = []): void {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        
        foreach ($headers as $header) {
            header($header);
        }

        echo json_encode($data);
        exit;
    }

    /**
     * Envía una respuesta de error estándar
     * @param string $message Mensaje descriptivo
     * @param int $statusCode Código HTTP de error
     */
    public function sendError(string $message, int $statusCode = 400): void {
        $this->sendJson([
            'error' => [
                'code' => $statusCode,
                'message' => $message
            ]
        ], $statusCode);
    }

    /**
     * Redirige a otra URL
     * @param string $url URL de destino
     * @param int $statusCode Código de redirección (ej: 301, 302)
     */
    public function redirect(string $url, int $statusCode = 302): void {
        header("Location: $url", true, $statusCode);
        exit;
    }
}