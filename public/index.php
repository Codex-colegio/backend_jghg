<?php
require __DIR__ . '/../vendor/autoload.php';

//esto captura errores fatales y los convierte en excepciones para que no de un mensaje de error desorganizado y dé uno legible y controlado
set_error_handler(function($severity, $message, $file, $line) {
    if (error_reporting() & $severity) {
        throw new \ErrorException($message, 0, $severity, $file, $line);
    }
});

try {
    //cargar variables de entorno desde .env
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();

    require __DIR__ . '/../app/Core/App.php';
    $app = new App\Core\App();
    //Cargar rutas
    require __DIR__ . '/../app/Routes/api.php';
    $app->run();

} 
catch (\Dotenv\Exception\InvalidPathException $e) {
    //Error especifico para problemas con .env
    http_response_code(500);
    echo json_encode([
        'error' => 'Error de configuración',
        'message' => 'No se pudo cargar el archivo .env. ' . $e->getMessage()
    ]);
    exit;
} 
catch (\Exception $e) {
    //cualquier otra excepción
    http_response_code(500);
    echo json_encode([
        'error' => 'Error interno del servidor',
        'message' => $e->getMessage()
    ]);
    exit;
}