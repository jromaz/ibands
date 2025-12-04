<?php
// api/save_property.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json');

// 1. Seguridad: Solo usuarios logueados con rol adecuado
$user = current_user();
if (!$user) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

// Permitir 'admin', 'admin_propiedades', 'inmobiliaria'
$allowedRoles = ['admin', 'admin_propiedades', 'inmobiliaria'];
$userRole = strtolower($user['role_name'] ?? '');

if (!in_array($userRole, $allowedRoles)) {
    http_response_code(403);
    echo json_encode(['error' => 'Permisos insuficientes para cargar propiedades']);
    exit;
}

// 2. Validación de Entrada (Backend)
$title = trim($_POST['title'] ?? '');
$price = (float)($_POST['price_total'] ?? 0);
$surface = (int)($_POST['surface_total'] ?? 0);
$lat = $_POST['lat'] ?? null;

$errors = [];
if (strlen($title) < 5) $errors[] = "El título es muy corto";
if ($price <= 0) $errors[] = "El precio debe ser mayor a 0";
if ($surface <= 0) $errors[] = "La superficie debe ser mayor a 0";
if (!$lat) $errors[] = "La ubicación es obligatoria";

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['error' => implode(', ', $errors)]);
    exit;
}

// 3. Guardado
try {
    $sql = "INSERT INTO investments (
        title, description, location_name, lat, lng, 
        operation_type, asset_type, price_total, surface_total, 
        owner_id, status
    ) VALUES (
        :title, :desc, :loc, :lat, :lng,
        :op, :asset, :price, :surface,
        :owner, 'active'
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':desc'  => $_POST['description'] ?? '',
        ':loc'   => $_POST['location_name'] ?? '',
        ':lat'   => $_POST['lat'],
        ':lng'   => $_POST['lng'],
        ':op'    => $_POST['operation_type'] ?? 'venta',
        ':asset' => $_POST['asset_type'] ?? 'casa',
        ':price' => $price,
        ':surface' => $surface,
        ':owner' => $user['id']
    ]);

    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en base de datos: ' . $e->getMessage()]);
}