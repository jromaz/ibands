<?php
require_once __DIR__ . '/includes/db.php';

try {
    // Check if role exists
    $stmt = $pdo->prepare("SELECT id FROM roles WHERE name = 'agente_inmobiliario'");
    $stmt->execute();
    $role = $stmt->fetch();

    if (!$role) {
        // Insert role
        $stmt = $pdo->prepare("INSERT INTO roles (name, description) VALUES ('agente_inmobiliario', 'Agente Inmobiliario - Ve productos terminados')");
        $stmt->execute();
        echo "Rol 'agente_inmobiliario' creado exitosamente.\n";
    } else {
        echo "El rol 'agente_inmobiliario' ya existe.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
