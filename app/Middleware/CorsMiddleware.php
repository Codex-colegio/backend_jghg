<?php
namespace App\Middleware;

class CorsMiddleware {
    public static function handle() {
        header("Access-Control-Allow-Origin: http://app.localhost");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
    }
}
