<?php
namespace App\Modules\Usuarios\Models;

class Usuario {
    private $db;

    public function __construct() {
        $this->db = require __DIR__ . '/../../../../config/database.php';
    }

    public function todas() {
        $stmt = $this->db->query("CALL sp_usuario_todas()");
        return $stmt->fetchAll();
    }

    public function buscarPorId($id) {
        $stmt = $this->db->prepare("CALL sp_usuario_buscar_por_id(:id)");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function buscarPorUsuario($usuario) {
        $stmt = $this->db->prepare("CALL sp_usuario_buscar_por_usuario(:usuario)");
        $stmt->execute(['usuario' => $usuario]);
        return $stmt->fetch();
    }
}