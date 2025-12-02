<?php
// includes/auth.php

// Aseguramos sesión activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Intentamos tener acceso a $pdo.
 * En la mayoría de los casos, db.php ya fue incluido antes.
 * Si no, lo cargamos desde acá.
 */
if (!isset($pdo) || !$pdo instanceof PDO) {
    $dbPath = __DIR__ . '/db.php';
    if (file_exists($dbPath)) {
        require_once $dbPath;
    }
}

/**
 * Obtiene el usuario actual desde la sesión.
 * Si no está en sesión pero hay user_id, lo busca en DB.
 *
 * @return array|null
 */
function current_user(): ?array
{
    if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
        return $_SESSION['user'];
    }

    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    if (!isset($GLOBALS['pdo']) || !$GLOBALS['pdo'] instanceof PDO) {
        return null;
    }

    $pdo = $GLOBALS['pdo'];

    $sql = "
        SELECT
          u.id,
          u.name,
          u.email,
          u.role_id,
          u.is_active,
          r.name AS role_name,
          r.description AS role_description
        FROM users u
        LEFT JOIN roles r ON r.id = u.role_id
        WHERE u.id = :id
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Si el usuario no existe más en BD, limpiamos sesión
        unset($_SESSION['user_id'], $_SESSION['user']);
        return null;
    }

    $_SESSION['user'] = $user;
    return $user;
}

/**
 * Indica si hay un usuario logueado.
 */
function is_logged_in(): bool
{
    return current_user() !== null;
}

/**
 * Carga en sesión un usuario a partir de su ID.
 * Se usa luego de un login exitoso.
 *
 * @param PDO $pdo
 * @param int $userId
 * @return void
 */
function load_user_into_session(PDO $pdo, int $userId): void
{
    $sql = "
        SELECT
          u.id,
          u.name,
          u.email,
          u.role_id,
          u.is_active,
          r.name AS role_name,
          r.description AS role_description
        FROM users u
        LEFT JOIN roles r ON r.id = u.role_id
        WHERE u.id = :id
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new RuntimeException('Usuario no encontrado al cargar sesión.');
    }

    $_SESSION['user_id'] = (int)$user['id'];
    $_SESSION['user']    = $user;
}

/**
 * Requiere que el usuario esté logueado, si no, redirige a login.
 */
function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Verifica si el usuario actual tiene un rol específico.
 *
 * @param string $roleName  (admin_propiedades, inversor, visitante, etc.)
 */
function has_role(string $roleName): bool
{
    $user = current_user();
    if (!$user) {
        return false;
    }

    $userRole = strtolower((string)($user['role_name'] ?? ''));
    return $userRole === strtolower($roleName);
}

/**
 * Cierra la sesión.
 */
function logout(): void
{
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    session_destroy();
}
