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
    public function obtenerPorId($id) {
        return $this->modelo->buscarPorId($id);
    }
}
