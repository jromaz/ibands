<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

require_login('admin');

$pdo = getPDO();

if (isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    $stmt = $pdo->prepare('DELETE FROM investments WHERE id = ?');
    $stmt->execute([$id]);
    header('Location: dashboard.php');
    exit;
}

$title = trim($_POST['title'] ?? '');
$location_name = trim($_POST['location_name'] ?? '');
$description = trim($_POST['description'] ?? '');
$owner_name = trim($_POST['owner_name'] ?? '');
$lat = (float)($_POST['lat'] ?? 0);
$lng = (float)($_POST['lng'] ?? 0);
$status = $_POST['status'] ?? 'planning';
$progress = (int)($_POST['progress_percent'] ?? 0);
$min_ticket = (float)($_POST['min_ticket'] ?? 0);

if (!$title || !$location_name || !$lat || !$lng) {
    header('Location: dashboard.php');
    exit;
}

$stmt = $pdo->prepare('INSERT INTO investments 
(title, location_name, description, owner_name, lat, lng, status, progress_percent, min_ticket)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
$stmt->execute([
    $title, $location_name, $description, $owner_name,
    $lat, $lng, $status, $progress, $min_ticket
]);

header('Location: dashboard.php');
