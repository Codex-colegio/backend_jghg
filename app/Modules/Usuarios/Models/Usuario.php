<?php
namespace App\Modules\Usuarios\Models;

class Usuario {
    private $db;

    public function __construct() {
        $this->db = require __DIR__ . '/../../../../config/database.php';
    }

    public function todas() {
        $stmt = $this->db->query("SELECT * FROM usuario");
        return $stmt->fetchAll();
    }

    public function buscarPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE idUsuario = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function buscarPorUsuario($usuario) {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE login = :usuario");
        $stmt->execute(['usuario' => $usuario]);
        return $stmt->fetch();
    }
}