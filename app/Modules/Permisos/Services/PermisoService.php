<?php
namespace App\Modules\Permisos\Services;

use App\Modules\Permisos\Models\Permiso;

class PermisoService {
    private $modelo;

    public function __construct() {
        $this->modelo = new Permiso();
    }

    public function obtenerTodas() {
        return $this->modelo->todas();
    }
    public function obtenerPorId($id) {
        return $this->modelo->buscarPorId($id);
    }

}
