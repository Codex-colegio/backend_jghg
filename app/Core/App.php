<?php
namespace App\Core;

class App {
    private $router;
    
    public function __construct() {
        $this->router = new Router();
    }

    public function getRouter(): Router {
        return $this->router;
    }

    public function run() {
        try {
            $this->router->dispatch();
        } catch (\PDOException $e) {
            // Errores especificos de base de datos
            $this->handleError("Error de base de datos: " . $e->getMessage(), 500);
        } catch (\Exception $e) {
            //Cualquier otra excepcion
            $this->handleError($e->getMessage(), 500);
        }
    }
    
    private function handleError($message, $statusCode) {
        $response = new Response();
        $response->sendError($message, $statusCode);
    }
}