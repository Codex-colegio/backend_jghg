<?php
namespace App\Modules\Usuarios\Services;

use App\Modules\Usuarios\Models\Usuario;

class UsuarioService {
    private $modelo;

    public function __construct() {
        $this->modelo = new Usuario();
    }

    public function obtenerTodas() {
        return $this->modelo->todas();
    }
    public function obtenerPorId($estado) {
        return $this->modelo->buscarPorId($estado);
    }
    public function obtenerPorUsuario($usuario) {
        return $this->modelo->buscarPorUsuario($usuario);
    }
}
