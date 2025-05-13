<?php
namespace App\Middleware;

use App\Helpers\JwtHelper;

// --- CORS (ya lo tenías) ---
header("Access-Control-Allow-Origin: http://app.localhost");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Manejo para solicitudes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

class AuthMiddleware {
    /**
     * Verifica el token JWT desde el encabezado Authorization.
     * Retorna el payload si es válido, o null si no lo es.
     */
    public static function verificarToken(): ?object {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if (!$authHeader || stripos($authHeader, 'Bearer ') !== 0) {
            return null;
        }

        $token = trim(str_replace('Bearer ', '', $authHeader));
        return JwtHelper::verificarToken($token); // Retorna el payload o null
    }
}
