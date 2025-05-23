<?php
namespace App\Helpers;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class JwtHelper {
    private static function getClave() {
        return $_ENV['JWT_SECRET'];
    }

    public static function generarToken($data, $exp = 3600) {
        $payload = [
            'iss' => 'swm_system',
            'aud' => 'swm_client',
            'iat' => time(),
            'exp' => time() + $exp,
            'data' => $data
        ];

        return JWT::encode($payload, self::getClave(), 'HS256');
    }

    public static function verificarToken($token) {
        try {
            return JWT::decode($token, new Key(self::getClave(), 'HS256')); //Decodifica y verifica el token
            
        } catch (\Exception $e) {
            return null;
        }
    }
}
