<?php
// Script para importar data.sql a PostgreSQL en Render
// ¡Recuerda borrar este archivo después de usarlo!

$sqlFile = __DIR__ . '/data.sql';

// Configuración de conexión usando variables de entorno de CodeIgniter
// DATOS REALES DE RENDER
$host = 'dpg-d01ushjubrs73b74c0g-a.oregon-postgres.render.com';
$db   = 'salon_belleza_db';
$user = 'salon_belleza_db_user';
$pass = 'oGrwxwt5v75gpPL00Df4PMhTeF0Ev1'; // Cambia aquí por tu contraseña real si la has cambiado
$port = 5432;

$dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require";
echo "<pre>";
echo "HOST: $host\n";
echo "PORT: $port\n";
echo "DB: $db\n";
echo "USER: $user\n";

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $sql = file_get_contents($sqlFile);
    if ($sql === false) {
        throw new Exception('No se pudo leer el archivo SQL.');
    }
    $pdo->beginTransaction();
    $pdo->exec($sql);
    $pdo->commit();
    echo "\nImportación exitosa.";
} catch (Exception $e) {
    echo "\nError: " . $e->getMessage();
}
echo "</pre>";

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
