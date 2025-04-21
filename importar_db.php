<?php
// Script para importar data.sql a PostgreSQL en Render
// ¡Recuerda borrar este archivo después de usarlo!

$sqlFile = __DIR__ . '/data.sql';

// Configuración de conexión usando variables de entorno de CodeIgniter
$host = getenv('database.default.hostname');
$db   = getenv('database.default.database');
$user = getenv('database.default.username');
$pass = getenv('database.default.password');
$port = getenv('database.default.port') ?: 5432;

$dsn = "pgsql:host=$host;port=$port;dbname=$db";
echo "<pre>";
echo "HOST: " . getenv('database.default.hostname') . "\n";
echo "PORT: " . getenv('database.default.port') . "\n";
echo "DB: " . getenv('database.default.database') . "\n";
echo "USER: " . getenv('database.default.username') . "\n";
echo "PASS: " . getenv('database.default.password') . "\n";
echo "</pre>";
try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $sql = file_get_contents($sqlFile);
    $pdo->exec($sql);
    echo "¡Importación exitosa! Elimina este archivo por seguridad.";
} catch (PDOException $e) {
    echo "Error al importar: " . $e->getMessage();
}
