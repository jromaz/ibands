<?php
require_once __DIR__ . '/includes/db.php';

try {
    // 1. Get Role ID
    $stmt = $pdo->prepare("SELECT id FROM roles WHERE name = 'agente_inmobiliario'");
    $stmt->execute();
    $role = $stmt->fetch();

    if (!$role) {
        die("Error: El rol 'agente_inmobiliario' no existe. Ejecuta primero migration_add_role.php\n");
    }

    $roleId = $role['id'];
    $email = 'horus@bands.com'; // Email inventado para el usuario
    $password = 'larioja';
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // 2. Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        // Update password and role
        $stmt = $pdo->prepare("UPDATE users SET password_hash = :hash, role_id = :role_id, name = 'Horus Agente' WHERE id = :id");
        $stmt->execute([':hash' => $hash, ':role_id' => $roleId, ':id' => $user['id']]);
        echo "Usuario 'horus' actualizado.\n";
    } else {
        // Create user
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role_id, is_active) VALUES ('Horus Agente', :email, :hash, :role_id, 1)");
        $stmt->execute([':email' => $email, ':hash' => $hash, ':role_id' => $roleId]);
        echo "Usuario 'horus' creado exitosamente.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
