<?php
// api/user_profile.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json; charset=utf-8');

$user = current_user();

if (!$user) {
    http_response_code(401);
    echo json_encode([
        'logged_in' => false,
        'error'     => 'Usuario no autenticado'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode([
    'logged_in'   => true,
    'id'          => (int)$user['id'],
    'name'        => $user['name'] ?? null,
    'email'       => $user['email'] ?? null,
    'role_id'     => isset($user['role_id']) ? (int)$user['role_id'] : null,
    'role'        => $user['role_name'] ?? null,
    'role_label'  => $user['role_name'] ?? null,
    'is_active'   => isset($user['is_active']) ? (int)$user['is_active'] : null,
], JSON_UNESCAPED_UNICODE);
