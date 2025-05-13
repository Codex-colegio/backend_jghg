<?php
$config = [
    'host' => $_ENV['DB_HOST'] ?? '',
    'dbname' => $_ENV['DB_NAME'] ?? '',
    'user' => $_ENV['DB_USER'] ?? '',
    'pass' => $_ENV['DB_PASS'] ?? ''
    ,'charset' => 'utf8mb4' //# para sql

];

// Verificar variables de entorno
if (empty($config['host']) || empty($config['dbname']) || empty($config['user'])) {
    throw new \Exception("Error de configuración: Variables de entorno no configuradas");
}
//usar para mysql
$dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

// Usar el driver de SQL Server
#$dsn = "sqlsrv:Server={$config['host']};Database={$config['dbname']}";

try {
    $pdo = new PDO($dsn, $config['user'], $config['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    throw new \Exception("Error de conexión a la base de datos: " . $e->getMessage());
}

return $pdo;
