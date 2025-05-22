<?php
namespace App\Modules\Login\Services;

use App\Modules\Login\Models\Login;

class LoginService {
    private $modelo;

    public function __construct() {
        $this->modelo = new Login();
    }

    public function obtenerPorUsuario($usuario) {
        return $this->modelo->buscarPorUsuario($usuario);
    }
}
