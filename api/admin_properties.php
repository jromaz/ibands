<?php
// api/admin_properties.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json; charset=utf-8');

/**
 * Helper: leer JSON desde el body.
 */
function read_json_input(): array
{
    $raw = file_get_contents('php://input');
    if (!$raw) {
        return [];
    }
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

/**
 * Helper: respuesta JSON con código.
 */
function json_response(int $status, array $payload): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Helper: validación simple tipo string.
 */
function v_string(array &$errors, array $src, string $key, int $maxLen, bool $required = false): ?string
{
    $val = $src[$key] ?? null;
    if ($val === null || $val === '') {
        if ($required) {
            $errors[] = "El campo '{$key}' es obligatorio.";
        }
        return null;
    }
    $val = trim((string)$val);
    if ($val === '' && $required) {
        $errors[] = "El campo '{$key}' es obligatorio.";
        return null;
    }
    if (mb_strlen($val) > $maxLen) {
        $errors[] = "El campo '{$key}' supera el máximo de {$maxLen} caracteres.";
    }
    return $val;
}

/**
 * Helper: validación numérica decimal.
 */
function v_decimal(array &$errors, array $src, string $key, bool $required = false): ?float
{
    $val = $src[$key] ?? null;
    if ($val === null || $val === '') {
        if ($required) {
            $errors[] = "El campo '{$key}' es obligatorio.";
        }
        return null;
    }
    if (!is_numeric($val)) {
        $errors[] = "El campo '{$key}' debe ser numérico.";
        return null;
    }
    return (float)$val;
}

/**
 * Helper: validación de lat/lng.
 */
function v_coord(array &$errors, array $src, string $key): ?float
{
    $val = $src[$key] ?? null;
    if ($val === null || $val === '') {
        return null; // no obligatorio
    }
    if (!is_numeric($val)) {
        $errors[] = "El campo '{$key}' debe ser numérico.";
        return null;
    }
    return (float)$val;
}

/**
 * Normaliza y valida datos de inversión / propiedad desde $input.
 */
function validate_investment_payload(array $input, bool $isUpdate = false): array
{
    $errors = [];
    $clean  = [];

    // Campos básicos
    $clean['title']          = v_string($errors, $input, 'title', 200, !$isUpdate);
    $clean['description']    = v_string($errors, $input, 'description', 2000, false);
    $clean['location_name']  = v_string($errors, $input, 'location_name', 255, false);
    $clean['operation_type'] = v_string($errors, $input, 'operation_type', 50, !$isUpdate);
    $clean['asset_type']     = v_string($errors, $input, 'asset_type', 50, false);
    $clean['status']         = v_string($errors, $input, 'status', 50, false) ?? 'published';
    $clean['developer_name'] = v_string($errors, $input, 'developer_name', 255, false);
    $clean['video_url']      = v_string($errors, $input, 'video_url', 255, false);
    $clean['image_url']      = v_string($errors, $input, 'image_url', 255, false);

    $clean['lat'] = v_coord($errors, $input, 'lat');
    $clean['lng'] = v_coord($errors, $input, 'lng');

    // Precio / ticket
    // Para inversión: min_ticket
    // Para propiedades: price_total
    $price = v_decimal($errors, $input, 'price_total', false);
    $clean['price_total'] = null;
    $clean['min_ticket']  = null;

    if ($price !== null) {
        $op = strtolower($clean['operation_type'] ?? '');
        if ($op === 'inversion') {
            $clean['min_ticket'] = $price;
        } else {
            $clean['price_total'] = $price;
        }
    }

    // Otros opcionales
    $clean['surface_m2'] = v_decimal($errors, $input, 'surface_m2', false);
    $clean['bedrooms']   = isset($input['bedrooms']) && $input['bedrooms'] !== ''
        ? (int)$input['bedrooms'] : null;
    $clean['bathrooms']  = isset($input['bathrooms']) && $input['bathrooms'] !== ''
        ? (int)$input['bathrooms'] : null;

    // Avance obra en caso de inversión
    $clean['progress_percent'] = isset($input['progress_percent']) && $input['progress_percent'] !== ''
        ? (int)$input['progress_percent'] : 0;

    return [$clean, $errors];
}

/* ----------------------------------------------------------
   CONTROL DE ACCESO
   ---------------------------------------------------------- */

$user = current_user();
if (!$user || !has_role('admin_propiedades')) {
    json_response(403, ['error' => 'Acceso denegado. Se requiere rol admin_propiedades.']);
}

/* ----------------------------------------------------------
   LÓGICA PRINCIPAL
   ---------------------------------------------------------- */

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    switch ($method) {
        case 'GET':
            handle_get($pdo);
            break;
        case 'POST':
            handle_post($pdo);
            break;
        case 'PUT':
        case 'PATCH':
            handle_put($pdo);
            break;
        case 'DELETE':
            handle_delete($pdo);
            break;
        default:
            json_response(405, ['error' => 'Método no permitido']);
    }
} catch (Throwable $e) {
    json_response(500, [
        'error'  => 'Error interno en admin_properties',
        'detail' => $e->getMessage()
    ]);
}

/* ----------------------------------------------------------
   HANDLERS
   ---------------------------------------------------------- */

function handle_get(PDO $pdo): void
{
    $where  = [];
    $params = [];

    if (!empty($_GET['status'])) {
        $where[] = 'status = :status';
        $params[':status'] = $_GET['status'];
    }
    if (!empty($_GET['operation_type'])) {
        $where[] = 'operation_type = :op';
        $params[':op'] = $_GET['operation_type'];
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
          operation_type,
          asset_type,
          price_total,
          surface_m2,
          bedrooms,
          bathrooms,
          developer_name,
          video_url,
          image_url
        FROM investments
    ";

    if ($where) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }

    $sql .= ' ORDER BY id DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

    json_response(200, ['items' => $rows]);
}

function handle_post(PDO $pdo): void
{
    $input = read_json_input();
    if (empty($input) && !empty($_POST)) {
        $input = $_POST;
    }

    [$clean, $errors] = validate_investment_payload($input, false);

    if ($errors) {
        json_response(422, ['error' => 'Datos inválidos', 'errors' => $errors]);
    }

    $sql = "
        INSERT INTO investments (
          title,
          description,
          lat,
          lng,
          location_name,
          status,
          min_ticket,
          progress_percent,
          operation_type,
          asset_type,
          price_total,
          surface_m2,
          bedrooms,
          bathrooms,
          developer_name,
          video_url,
          image_url
        ) VALUES (
          :title,
          :description,
          :lat,
          :lng,
          :location_name,
          :status,
          :min_ticket,
          :progress_percent,
          :operation_type,
          :asset_type,
          :price_total,
          :surface_m2,
          :bedrooms,
          :bathrooms,
          :developer_name,
          :video_url,
          :image_url
        )
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':title'           => $clean['title'],
        ':description'     => $clean['description'],
        ':lat'             => $clean['lat'],
        ':lng'             => $clean['lng'],
        ':location_name'   => $clean['location_name'],
        ':status'          => $clean['status'],
        ':min_ticket'      => $clean['min_ticket'],
        ':progress_percent'=> $clean['progress_percent'],
        ':operation_type'  => $clean['operation_type'],
        ':asset_type'      => $clean['asset_type'],
        ':price_total'     => $clean['price_total'],
        ':surface_m2'      => $clean['surface_m2'],
        ':bedrooms'        => $clean['bedrooms'],
        ':bathrooms'       => $clean['bathrooms'],
        ':developer_name'  => $clean['developer_name'],
        ':video_url'       => $clean['video_url'],
        ':image_url'       => $clean['image_url'],
    ]);

    $newId = (int)$pdo->lastInsertId();
    json_response(201, ['message' => 'Registro creado', 'id' => $newId]);
}

function handle_put(PDO $pdo): void
{
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($id <= 0) {
        json_response(400, ['error' => 'ID inválido para actualización.']);
    }

    $input = read_json_input();
    if (empty($input) && !empty($_POST)) {
        $input = $_POST;
    }

    [$clean, $errors] = validate_investment_payload($input, true);

    if ($errors) {
        json_response(422, ['error' => 'Datos inválidos', 'errors' => $errors]);
    }

    $sql = "
        UPDATE investments SET
          title           = COALESCE(:title, title),
          description     = COALESCE(:description, description),
          lat             = COALESCE(:lat, lat),
          lng             = COALESCE(:lng, lng),
          location_name   = COALESCE(:location_name, location_name),
          status          = COALESCE(:status, status),
          operation_type  = COALESCE(:operation_type, operation_type),
          asset_type      = COALESCE(:asset_type, asset_type),
          progress_percent= COALESCE(:progress_percent, progress_percent),
          surface_m2      = COALESCE(:surface_m2, surface_m2),
          bedrooms        = COALESCE(:bedrooms, bedrooms),
          bathrooms       = COALESCE(:bathrooms, bathrooms),
          developer_name  = COALESCE(:developer_name, developer_name),
          video_url       = COALESCE(:video_url, video_url),
          image_url       = COALESCE(:image_url, image_url),
          min_ticket      = CASE
                              WHEN :min_ticket IS NOT NULL THEN :min_ticket
                              ELSE min_ticket
                            END,
          price_total     = CASE
                              WHEN :price_total IS NOT NULL THEN :price_total
                              ELSE price_total
                            END
        WHERE id = :id
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id'             => $id,
        ':title'          => $clean['title'],
        ':description'    => $clean['description'],
        ':lat'            => $clean['lat'],
        ':lng'            => $clean['lng'],
        ':location_name'  => $clean['location_name'],
        ':status'         => $clean['status'],
        ':operation_type' => $clean['operation_type'],
        ':asset_type'     => $clean['asset_type'],
        ':progress_percent'=> $clean['progress_percent'],
        ':surface_m2'     => $clean['surface_m2'],
        ':bedrooms'       => $clean['bedrooms'],
        ':bathrooms'      => $clean['bathrooms'],
        ':developer_name' => $clean['developer_name'],
        ':video_url'      => $clean['video_url'],
        ':image_url'      => $clean['image_url'],
        ':min_ticket'     => $clean['min_ticket'],
        ':price_total'    => $clean['price_total'],
    ]);

    json_response(200, ['message' => 'Registro actualizado']);
}

function handle_delete(PDO $pdo): void
{
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($id <= 0) {
        json_response(400, ['error' => 'ID inválido para eliminar.']);
    }

    // Borrado lógico: status = 'archived'
    $sql = "UPDATE investments SET status = 'archived' WHERE id = :id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    json_response(200, ['message' => 'Registro archivado']);
}
