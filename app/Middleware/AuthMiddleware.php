<?php
namespace App\Middleware;

use App\Helpers\JwtHelper;

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
