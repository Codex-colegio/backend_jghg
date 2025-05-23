<?php
namespace App\Modules\Permisos\Models;

class Permiso {
    private $db;

    public function __construct() {
        $this->db = require __DIR__ . '/../../../../config/database.php';
    }

    public function todas() {
        $stmt = $this->db->query("CALL sp_usuario_todas()");
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (!$result) return null;

        // Tomamos los datos del usuario de la primera fila
        $usuarioData = $result[0];

        // Agrupamos todos los permisos
        $permisos = [];
        foreach ($result as $row) {
            $permisos[] = [
                'id_permiso' => $row['id_permiso']
            ];
        }

        // Retornamos los datos completos
        $usuarioData['permisos'] = $permisos;

        return $usuarioData;
        return $stmt->fetchAll();
    }

    public function buscarPorId($id) {
        $stmt = $this->db->prepare("CALL sp_permisos_por_usuario(:id)");
        $stmt->execute(['id' => $id]);
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