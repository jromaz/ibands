<?php
// api/investments.php
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    if (!isset($pdo)) {
        throw new RuntimeException('Conexión PDO $pdo no definida en db.php');
    }

    $sql = "
        SELECT
            id,
            title,
            description,
            lat,
            lng,
            location_name,
            status,
            min_ticket,
            progress_percent,
            image_url
        FROM investments
        ORDER BY id DESC
    ";

    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

    echo json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al obtener inversiones',
        'detail' => $e->getMessage()
    ]);
}
