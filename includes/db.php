<?php
// includes/db.php

// Iniciamos sesión temprano (lo usa auth.php y otras partes)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Intentamos cargar configuración externa si existe
$configPath = __DIR__ . '/../config/config.php';
if (file_exists($configPath)) {
    require_once $configPath;
}

// Valores por defecto (si no hay constantes definidas en config.php)
$dbHost = defined('DB_HOST') ? DB_HOST : 'localhost';
$dbName = defined('DB_NAME') ? DB_NAME : 'bands_inversiones';
$dbUser = defined('DB_USER') ? DB_USER : 'root';
$dbPass = defined('DB_PASS') ? DB_PASS : '';

try {
    // Evitar recrear el PDO si este archivo se incluye varias veces
    if (!isset($pdo) || !$pdo instanceof PDO) {
        $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";
        $pdo = new PDO($dsn, $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
} catch (PDOException $e) {
    // En producción podrías loguear el error en vez de mostrarlo
    die('Error de conexión a la base de datos: ' . $e->getMessage());
}
