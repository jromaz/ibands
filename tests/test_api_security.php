<?php
// tests/test_api_security.php

// Mock DB connection
require_once __DIR__ . '/../includes/db.php';

// Helper to reset session and run API
function test_api_as_role($roleName, $pdo) {
    // Reset session
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
    session_start();
    $_SESSION = []; // Clear

    // Mock User
    if ($roleName) {
        $_SESSION['user'] = [
            'id' => 999,
            'name' => 'Test User',
            'role_name' => $roleName
        ];
        $_SESSION['user_id'] = 999;
    }

    // Capture Output
    ob_start();
    // We need to isolate the include so it doesn't kill the test script with exit()
    // But api/investments.php might not have exit() if we are lucky, 
    // or we can wrap it. 
    // Actually, api/investments.php usually just echos. 
    // Let's try including it. 
    // WARNING: If api/investments.php does `exit`, this script stops.
    // Let's check api/investments.php content again. 
    // It does NOT have exit(), it just echos. Good.
    
    include __DIR__ . '/../api/investments.php';
    $output = ob_get_clean();
    
    return json_decode($output, true);
}

echo "--- Iniciando Test de Seguridad API ---\n";

// 1. Test como GUEST
echo "\n[TEST] Rol: Guest (Sin sesión)\n";
$data = test_api_as_role(null, $pdo);
if (isset($data[0])) {
    $item = $data[0];
    if (!isset($item['min_ticket']) && !isset($item['roi_estimado']) && $item['progress_percent'] == 100) {
        echo "✅ PASÓ: Datos sensibles ocultos. Progress = 100%.\n";
    } else {
        echo "❌ FALLÓ: Se filtraron datos sensibles o progress no es 100%.\n";
        print_r($item);
    }
} else {
    echo "⚠️ No hay datos para probar.\n";
}

// 2. Test como AGENTE
echo "\n[TEST] Rol: agente_inmobiliario\n";
$data = test_api_as_role('agente_inmobiliario', $pdo);
if (isset($data[0])) {
    $item = $data[0];
    if (!isset($item['min_ticket']) && $item['progress_percent'] == 100) {
        echo "✅ PASÓ: Datos sensibles ocultos para Agente.\n";
    } else {
        echo "❌ FALLÓ: Agente vio datos prohibidos.\n";
        print_r($item);
    }
}

// 3. Test como INVERSOR
echo "\n[TEST] Rol: inversor\n";
$data = test_api_as_role('inversor', $pdo);
if (isset($data[0])) {
    $item = $data[0];
    // Inversor should see min_ticket (if it exists in DB) and real progress
    // We assume the DB has min_ticket.
    if (array_key_exists('min_ticket', $item) && $item['view_mode'] === 'investor') {
        echo "✅ PASÓ: Inversor ve datos completos.\n";
    } else {
        echo "❌ FALLÓ: Inversor no vio datos completos.\n";
        print_r($item);
    }
}

echo "\n--- Fin del Test ---\n";
