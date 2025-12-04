<?php
// property.php - Ficha de detalle estilo Airbnb
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

// 1. Validar ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$prop = null;

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM investments WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $prop = $stmt->fetch();
}

if (!$prop) {
    header("Location: index.php");
    exit;
}

$pageTitle = $prop['title'];
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/navbar.php';
?>

<main class="container py-5" style="margin-top: 60px;">
    
    <!-- HEADER: Título y Acciones -->
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="fw-bold mb-1"><?= htmlspecialchars($prop['title']) ?></h1>
            <div class="small text-muted">
                <i class="bi bi-geo-alt-fill me-1"></i><?= htmlspecialchars($prop['location_name']) ?>
                <span class="mx-2">·</span>
                <span class="text-capitalize"><?= $prop['operation_type'] ?></span>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-dark rounded-pill"><i class="bi bi-share me-1"></i> Compartir</button>
            <button class="btn btn-sm btn-outline-dark rounded-pill"><i class="bi bi-heart me-1"></i> Guardar</button>
        </div>
    </div>

    <!-- GALERÍA (Placeholder visual) -->
    <div class="row g-2 mb-4 rounded-4 overflow-hidden" style="height: 400px;">
        <div class="col-6 h-100">
            <img src="<?= $prop['image_url'] ?: 'assets/img/placeholder.jpg' ?>" class="w-100 h-100 object-fit-cover" alt="Main">
        </div>
        <div class="col-6 h-100">
            <div class="row g-2 h-50 mb-2">
                <div class="col-6"><img src="https://via.placeholder.com/600x400?text=Cocina" class="w-100 h-100 object-fit-cover"></div>
                <div class="col-6"><img src="https://via.placeholder.com/600x400?text=Baño" class="w-100 h-100 object-fit-cover"></div>
            </div>
            <div class="row g-2 h-50">
                <div class="col-6"><img src="https://via.placeholder.com/600x400?text=Vista" class="w-100 h-100 object-fit-cover"></div>
                <div class="col-6 position-relative">
                    <img src="https://via.placeholder.com/600x400?text=Plano" class="w-100 h-100 object-fit-cover">
                    <button class="btn btn-light btn-sm position-absolute bottom-0 end-0 m-3 shadow-sm">Ver todas las fotos</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- COLUMNA IZQUIERDA: Info Detallada -->
        <div class="col-lg-8">
            <div class="d-flex justify-content-between py-3 border-bottom">
                <div>
                    <h2 class="h5 fw-bold mb-1">Inmueble tipo <?= htmlspecialchars($prop['asset_type']) ?></h2>
                    <ol class="breadcrumb bg-white p-0 small text-muted mb-0">
                        <li class="breadcrumb-item"><?= $prop['bedrooms'] ?> habitaciones</li>
                        <li class="breadcrumb-item"><?= $prop['bathrooms'] ?> baños</li>
                        <li class="breadcrumb-item"><?= $prop['surface_total'] ?> m²</li>
                    </ol>
                </div>
                <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-house-door"></i>
                </div>
            </div>

            <div class="py-4 border-bottom">
                <h3 class="h6 fw-bold">Descripción</h3>
                <p class="text-muted"><?= nl2br(htmlspecialchars($prop['description'])) ?></p>
            </div>

            <!-- MAPA -->
            <div class="py-4">
                <h3 class="h6 fw-bold mb-3">Dónde vas a estar</h3>
                <div id="propertyMap" style="height: 320px; border-radius: 12px;" class="bg-light"></div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: Sticky Card (Precio/Contacto) -->
        <div class="col-lg-4">
            <div class="card shadow-lg border-0 rounded-4 p-4 sticky-top" style="top: 100px;">
                <div class="d-flex justify-content-between align-items-baseline mb-3">
                    <div>
                        <span class="fs-4 fw-bold">$<?= number_format($prop['price_total'] ?: $prop['min_ticket']) ?></span>
                        <span class="small text-muted"> total</span>
                    </div>
                    <div class="small fw-bold text-dark">
                        <i class="bi bi-star-fill text-warning me-1"></i> 4.85
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-primary btn-lg rounded-3 fw-bold">
                        Consultar disponibilidad
                    </button>
                    <button class="btn btn-outline-secondary rounded-3">
                        Agendar visita
                    </button>
                </div>
                
                <div class="mt-3 text-center small text-muted">
                    No se te cobrará nada todavía.
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Inicializar Mapa centrado en la propiedad
    const lat = <?= $prop['lat'] ?>;
    const lng = <?= $prop['lng'] ?>;
    
    if (window.maplibregl) {
        const map = new maplibregl.Map({
            container: 'propertyMap',
            style: `https://api.maptiler.com/maps/streets/style.json?key=${window.MAPTILER_KEY}`,
            center: [lng, lat],
            zoom: 14,
            interactive: false // Estático estilo Airbnb
        });
        
        // Marcador grande
        const el = document.createElement('div');
        el.className = 'marker';
        el.style.backgroundImage = 'url(assets/img/BandS.png)'; // Tu icono
        el.style.width = '40px';
        el.style.height = '40px';
        el.style.backgroundSize = '100%';

        new maplibregl.Marker({ color: '#ff6a00' })
            .setLngLat([lng, lat])
            .addTo(map);
    }
});
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>