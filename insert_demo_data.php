<?php
/**
 * Insertar datos de prueba para BandS Inversiones
 * 
 * Uso:
 * 1. Copiar este archivo en la raíz del proyecto (junto a index.php)
 * 2. Navegar a http://localhost/bandsinversiones/insert_demo_data.php
 * 3. Ver resultado en pantalla
 * 4. (Opcional) Borrar este archivo cuando ya no se use
 */

require_once __DIR__ . '/includes/db.php';

try {
    $pdo = getPDO();

    // Comprobamos si ya existen datos similares para evitar duplicados
    $stmtCheck = $pdo->prepare("
        SELECT COUNT(*) 
        FROM investments 
        WHERE title = :title 
          AND location_name = :location_name
    ");

    $stmtInsert = $pdo->prepare("
        INSERT INTO investments
        (title, description, lat, lng, location_name, status, min_ticket, progress_percent, owner_id)
        VALUES
        (:title, :description, :lat, :lng, :location_name, :status, :min_ticket, :progress_percent, :owner_id)
    ");

    // ⚠ IMPORTANTE:
    // Ajustá este owner_id si tu usuario admin tiene otro ID.
    $defaultOwnerId = 1;

    $demoInvestments = [
        [
            'title'            => 'Torre Capital I',
            'description'      => 'Edificio residencial premium en pleno microcentro. Departamentos 1 y 2 dormitorios.',
            'lat'              => -29.41389,
            'lng'              => -66.85531,
            'location_name'    => 'Centro – Plaza 25 de Mayo',
            'status'           => 'construction',
            'min_ticket'       => 25000,
            'progress_percent' => 45,
        ],
        [
            'title'            => 'Bulevar Norte Residencias',
            'description'      => 'Conjunto de 3 torres con amenities. Vista panorámica de la ciudad.',
            'lat'              => -29.40452,
            'lng'              => -66.84620,
            'location_name'    => 'Zona Norte – Av. Ortiz de Ocampo',
            'status'           => 'planning',
            'min_ticket'       => 30000,
            'progress_percent' => 10,
        ],
        [
            'title'            => 'Parque Industrial Logistic Hub',
            'description'      => 'Nave logística con infraestructura para PyMEs y depósitos.',
            'lat'              => -29.38272,
            'lng'              => -66.84189,
            'location_name'    => 'Parque Industrial – Acceso Sur',
            'status'           => 'construction',
            'min_ticket'       => 40000,
            'progress_percent' => 55,
        ],
        [
            'title'            => 'Residencias UNLaR',
            'description'      => 'Viviendas modernas para estudiantes universitarios. 48 unidades.',
            'lat'              => -29.44201,
            'lng'              => -66.85612,
            'location_name'    => 'Zona Universitaria – UNLaR',
            'status'           => 'finished',
            'min_ticket'       => 20000,
            'progress_percent' => 100,
        ],
        [
            'title'            => 'Condominio Parque Sur',
            'description'      => 'Barrio cerrado con áreas verdes, salón y pileta.',
            'lat'              => -29.46488,
            'lng'              => -66.83331,
            'location_name'    => 'Parque Sur',
            'status'           => 'construction',
            'min_ticket'       => 18000,
            'progress_percent' => 40,
        ],
        [
            'title'            => 'Torre Libertad II',
            'description'      => 'Edificio de 14 pisos con locales comerciales en planta baja.',
            'lat'              => -29.41222,
            'lng'              => -66.85250,
            'location_name'    => 'Microcentro – Libertad',
            'status'           => 'planning',
            'min_ticket'       => 35000,
            'progress_percent' => 5,
        ],
        [
            'title'            => 'Complejo Antártida VIP',
            'description'      => 'Conjunto habitacional premium con cocheras subterráneas.',
            'lat'              => -29.40790,
            'lng'              => -66.86542,
            'location_name'    => 'Barrio Antártida',
            'status'           => 'finished',
            'min_ticket'       => 45000,
            'progress_percent' => 100,
        ],
        [
            'title'            => 'Paseo del Sol Housing',
            'description'      => 'Viviendas familiares modernas en zona de alto crecimiento urbano.',
            'lat'              => -29.43114,
            'lng'              => -66.83690,
            'location_name'    => 'Barrio Vargas – Paseo del Sol',
            'status'           => 'construction',
            'min_ticket'       => 22000,
            'progress_percent' => 65,
        ],
        [
            'title'            => 'Portal del Este',
            'description'      => 'Barrio semi-privado con lotes disponibles y viviendas llave en mano.',
            'lat'              => -29.42522,
            'lng'              => -66.82533,
            'location_name'    => 'Zona Este – Ruta 5',
            'status'           => 'planning',
            'min_ticket'       => 15000,
            'progress_percent' => 20,
        ],
        [
            'title'            => 'EcoResidencias Terminal',
            'description'      => 'Edificio sostenible con paneles solares y jardines verticales.',
            'lat'              => -29.41867,
            'lng'              => -66.84357,
            'location_name'    => 'Zona Terminal de Ómnibus',
            'status'           => 'construction',
            'min_ticket'       => 27000,
            'progress_percent' => 33,
        ],
    ];

    $inserted = 0;
    $skipped  = 0;

    foreach ($demoInvestments as $inv) {
        // ¿Ya existe algo igual? (mismo título + misma ubicación)
        $stmtCheck->execute([
            ':title'         => $inv['title'],
            ':location_name' => $inv['location_name'],
        ]);

        $exists = (int)$stmtCheck->fetchColumn() > 0;

        if ($exists) {
            $skipped++;
            continue;
        }

        $stmtInsert->execute([
            ':title'            => $inv['title'],
            ':description'      => $inv['description'],
            ':lat'              => $inv['lat'],
            ':lng'              => $inv['lng'],
            ':location_name'    => $inv['location_name'],
            ':status'           => $inv['status'],
            ':min_ticket'       => $inv['min_ticket'],
            ':progress_percent' => $inv['progress_percent'],
            ':owner_id'         => $defaultOwnerId,
        ]);

        $inserted++;
    }

    header('Content-Type: text/plain; charset=utf-8');
    echo "Seed de demo ejecutado.\n";
    echo "Nuevas inversiones insertadas: {$inserted}\n";
    echo "Inversiones omitidas (ya existían): {$skipped}\n";

} catch (Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Error al ejecutar seed de demo:\n";
    echo $e->getMessage();
}
