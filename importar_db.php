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

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $sql = file_get_contents($sqlFile);
    $pdo->exec($sql);
    echo "¡Importación exitosa! Elimina este archivo por seguridad.";
} catch (PDOException $e) {
    echo "Error al importar: " . $e->getMessage();
}
