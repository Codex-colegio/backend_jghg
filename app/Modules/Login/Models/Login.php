<?php
namespace App\Modules\Login\Models;

class Login {
    private $db;

    public function __construct() {
        $this->db = require __DIR__ . '/../../../../config/database.php';
    }

    public function buscarPorUsuario($usuario) {
        $stmt = $this->db->prepare("CALL sp_login_por_usuario(:usuario)");
        $stmt->execute(['usuario' => $usuario]);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (!$result) return null;

        // Tomamos los datos del usuario de la primera fila
        $usuarioData = $result[0];

        // Agrupamos todos los permisos
        $permisos = [];
        foreach ($result as $row) {
            $permisos[] = [
                'id_permiso' => $row['id_permiso'],
                'nom_permiso' => $row['nom_permiso']
            ];
        }

        // Retornamos los datos completos
        $usuarioData['permisos'] = $permisos;

        return $usuarioData;
    }

}