<?php

$config = require __DIR__ . '/../../config/db.php';

$host    = $config['host'] ?? 'localhost';
$db      = $config['dbname'] ?? '';
$user    = $config['user'] ?? 'root';
$pass    = $config['pass'] ?? '';
$charset = $config['charset'] ?? 'utf8mb4';
$port    = $config['port'] ?? '3306';

try {
    $dsn = "mysql:host=$host;dbname=$db;port=$port;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    return $pdo;
} catch (PDOException $e) {
    die("❌ Error en la conexión: " . $e->getMessage());
}
