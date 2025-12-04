<?php
// api/investments.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json; charset=utf-8');

try {
    if (!isset($pdo)) {
        throw new RuntimeException('Conexión PDO $pdo no definida en db.php');
    }

    // 1. Detectar Rol
    $user = current_user();
    $role = strtolower($user['role_name'] ?? 'guest');
    
    // Roles que ven data financiera (Inversores/Admins)
    $financialRoles = ['admin', 'admin_propiedades', 'inversor'];
    $canSeeFinancials = in_array($role, $financialRoles);

    // 2. Query Base con Filtros
    $where = ["status != 'archived'"];
    $params = [];

    // Filtros de Búsqueda
    $category = $_GET['category'] ?? ''; // lotes, venta, alquiler, temporal
    $zone     = $_GET['zone'] ?? '';
    $price    = $_GET['price'] ?? '';
    
    // Mapeo de Categoría a DB
    if ($category === 'lotes') {
        $where[] = "asset_type = 'lote'";
        $where[] = "operation_type IN ('venta', 'inversion')"; // Asumimos lotes son venta o inversión
    } elseif ($category === 'venta') {
        $where[] = "operation_type = 'venta'";
        $where[] = "asset_type != 'lote'";
    } elseif ($category === 'alquiler') {
        $where[] = "operation_type = 'alquiler'";
    } elseif ($category === 'temporal') {
        $where[] = "operation_type = 'alquiler_temporal'";
    }

    // Filtro por Zona (Búsqueda parcial)
    if ($zone) {
        $where[] = "(location_name LIKE :zone OR title LIKE :zone)";
        $params[':zone'] = "%$zone%";
    }

    // Filtro por Precio (Máximo)
    if ($price && is_numeric($price)) {
        // Puede ser price_total o min_ticket dependiendo del rol/tipo
        // Simplificación: filtramos por price_total si existe, o min_ticket
        $where[] = "(price_total <= :price OR min_ticket <= :price)";
        $params[':price'] = $price;
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
            image_url,
            operation_type,
            price_total,
            asset_type
        FROM investments
        WHERE " . implode(' AND ', $where) . "
        ORDER BY id DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

    // 3. Filtrado y Sanitización (Polimorfismo de Datos)
    $output = array_map(function($row) use ($canSeeFinancials) {
        
        // Lógica de Negocio:
        // Si NO puede ver financieros -> Es un "Producto Terminado"
        if (!$canSeeFinancials) {
            // Ocultar datos sensibles
            unset($row['min_ticket']);
            unset($row['roi_estimado']); // Si existiera
            
            // Mentira piadosa: Para un visitante, la obra siempre está "Lista" (o irrelevante)
            // O simplemente ocultamos el progress bar en el front, pero mandamos 100 para evitar nulos.
            $row['progress_percent'] = 100; 
            
            // Flag para el frontend: Saber que es vista "Visitante/Agente"
            $row['view_mode'] = 'retail'; 
        } else {
            // Inversor: Ve todo
            $row['view_mode'] = 'investor';
        }

        return $row;
    }, $rows);

    echo json_encode($output, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al obtener inversiones',
        'detail' => $e->getMessage()
    ]);
}
