<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

header('Content-Type: application/json; charset=utf-8');

if (!current_user()) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'login_required']);
    exit;
}

$payload = $_POST ?: json_decode(file_get_contents('php://input'), true);

$investmentId = isset($payload['investment_id']) ? (int)$payload['investment_id'] : 0;
if ($investmentId <= 0) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'invalid_id']);
    exit;
}

$pdo = getPDO();
$userId = current_user()['id'];

$stmt = $pdo->prepare('SELECT id FROM favorites WHERE user_id = ? AND investment_id = ?');
$stmt->execute([$userId, $investmentId]);
$row = $stmt->fetch();

if ($row) {
    $pdo->prepare('DELETE FROM favorites WHERE id = ?')->execute([$row['id']]);
    $isFav = false;
} else {
    $pdo->prepare('INSERT INTO favorites (user_id, investment_id) VALUES (?, ?)')->execute([$userId, $investmentId]);
    $isFav = true;
}

echo json_encode(['ok' => true, 'favorite' => $isFav]);
