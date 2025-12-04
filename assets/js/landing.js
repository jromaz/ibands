/**
 * assets/js/landing.js
 * Lógica específica para la Landing Page con comportamiento polimórfico.
 */

document.addEventListener("DOMContentLoaded", () => {
    // Check for search params in URL (from landing search)
    const urlParams = new URLSearchParams(window.location.search);
    const filters = {
        category: urlParams.get('category') || '',
        zone: urlParams.get('lotes_zona') || urlParams.get('venta_zona') || urlParams.get('alquiler_zona') || urlParams.get('temporal_zona') || '',
        price: urlParams.get('lotes_precio') || urlParams.get('alquiler_precio') || ''
    };

    fetchInvestments(filters);
    setupMapDrawer();
    setupBlueMode();
});

let mapInstance = null;
let allInvestments = [];

async function fetchInvestments(filters = {}) {
    try {
        // Build Query String
        const params = new URLSearchParams();
        if (filters.category) params.append('category', filters.category);
        if (filters.zone) params.append('zone', filters.zone);
        if (filters.price) params.append('price', filters.price);

        const res = await fetch(`api/investments.php?${params.toString()}`);
        const data = await res.json();

        if (Array.isArray(data)) {
            allInvestments = data;
            renderGrid(data);
            if (mapInstance) {
                addMarkers(data); // Refresh map if open
            }
        }
    } catch (err) {
        console.error("Error fetching investments:", err);
    }
}

function renderGrid(list) {
    const container = document.getElementById('investmentsList');
    if (!container) return;

    container.innerHTML = '';

    if (list.length === 0) {
        container.innerHTML = '<div class="col-12 text-center py-5 text-muted">No se encontraron resultados.</div>';
        return;
    }

    // Grid wrapper
    const row = document.createElement('div');
    row.className = 'row g-4';

    list.forEach(inv => {
        const col = document.createElement('div');
        col.className = 'col-12 col-md-6 col-lg-4 col-xl-3';
        col.appendChild(createCard(inv));
        row.appendChild(col);
    });

    container.appendChild(row);
}

function createCard(inv) {
    const card = document.createElement('div');
    // Determinar estilo según el modo de vista (Investor vs Retail)
    const isInvestor = inv.view_mode === 'investor';

    // Clases base
    card.className = 'card h-100 border-0 shadow-sm rounded-4 overflow-hidden bands-card-hover';

    // Colores sutiles: Azul (Venta/Retail) vs Naranja (Inversión)
    const badgeColor = isInvestor ? 'bg-warning text-dark' : 'bg-primary text-white';
    const badgeText = isInvestor ? 'Oportunidad de Inversión' : 'Venta / Alquiler';

    const img = inv.image_url || 'assets/img/placeholder.jpg';
    const price = inv.price_total
        ? `USD ${Number(inv.price_total).toLocaleString()}`
        : (inv.min_ticket ? `Desde USD ${Number(inv.min_ticket).toLocaleString()}` : 'Consultar');

    // HTML Condicional
    let footerHtml = '';
    if (isInvestor) {
        // Inversor: Ve barra de progreso y datos financieros
        footerHtml = `
            <div class="mt-3">
                <div class="d-flex justify-content-between small text-muted mb-1">
                    <span>Avance de obra</span>
                    <span>${inv.progress_percent}%</span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: ${inv.progress_percent}%"></div>
                </div>
            </div>
        `;
    } else {
        // Retail: Ve características simples
        footerHtml = `
            <div class="mt-3 d-flex gap-2 text-muted small">
                <span><i class="bi bi-arrows-fullscreen"></i> ${inv.surface_total || '-'} m²</span>
                <span><i class="bi bi-house"></i> ${inv.asset_type || 'Propiedad'}</span>
            </div>
        `;
    }

    card.innerHTML = `
        <div class="position-relative">
            <img src="${img}" class="card-img-top object-fit-cover" style="height: 220px;" alt="${inv.title}">
            <span class="position-absolute top-0 start-0 m-3 badge ${badgeColor} shadow-sm">${badgeText}</span>
            <button class="btn btn-light btn-sm rounded-circle position-absolute top-0 end-0 m-3 shadow-sm" onclick="toggleFav(this)">
                <i class="bi bi-heart"></i>
            </button>
        </div>
        <div class="card-body p-3 d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="card-title h6 fw-bold mb-0 text-truncate-2">${inv.title}</h5>
            </div>
            <p class="card-text small text-muted mb-auto"><i class="bi bi-geo-alt me-1"></i>${inv.location_name}</p>
            
            <div class="fw-bold fs-5 mt-2 text-dark">${price}</div>
            
            ${footerHtml}
            
            <!-- Oculus & QR (Always visible) -->
            <div class="d-flex justify-content-center gap-2 mt-3 pt-3 border-top">
                <a href="#" class="btn btn-sm btn-outline-secondary rounded-pill" onclick="event.stopPropagation(); alert('Abriendo vista Oculus...');">
                    <i class="bi bi-box me-1"></i> Oculus
                </a>
                <a href="#" class="btn btn-sm btn-outline-secondary rounded-pill" onclick="event.stopPropagation(); alert('Generando QR...');">
                    <i class="bi bi-qr-code me-1"></i> QR
                </a>
            </div>
        </div>
        <a href="properties.php?id=${inv.id}" class="stretched-link"></a>
    `;

    return card;
}

// =============================
// MAPA DRAWER (Acordeón)
// =============================
function setupMapDrawer() {
    const toggleBtn = document.getElementById('toggleMapBtn');
    const mapContainer = document.getElementById('mapDrawer');

    if (!toggleBtn || !mapContainer) return;

    toggleBtn.addEventListener('click', (e) => {
        e.preventDefault();

        const isHidden = mapContainer.classList.contains('d-none');

        if (isHidden) {
            // Mostrar
            mapContainer.classList.remove('d-none');
            mapContainer.style.height = '0px';

            // Animación simple con CSS transition si se desea, o JS directo
            requestAnimationFrame(() => {
                mapContainer.style.height = '400px'; // Altura fija o dinámica
                initMapIfNeeded();
            });

            toggleBtn.innerHTML = '<i class="bi bi-map-fill me-2"></i>Ocultar Mapa';
        } else {
            // Ocultar
            mapContainer.style.height = '0px';
            setTimeout(() => {
                mapContainer.classList.add('d-none');
            }, 300); // Esperar transición

            toggleBtn.innerHTML = '<i class="bi bi-map me-2"></i>Mostrar Mapa';
        }
    });
}

function initMapIfNeeded() {
    if (mapInstance) return; // Ya inicializado

    const mapEl = document.getElementById('map');
    if (!mapEl) return;

    // Usar MapLibre
    const key = window.MAPTILER_KEY || '';
    mapInstance = new maplibregl.Map({
        container: 'map',
        style: `https://api.maptiler.com/maps/streets/style.json?key=${key}`,
        center: [-58.3816, -34.6037], // Default BA
        zoom: 12
    });

    mapInstance.on('load', () => {
        addMarkers(allInvestments);
    });
}

function addMarkers(list) {
    if (!mapInstance) return;

    list.forEach(inv => {
        if (!inv.lat || !inv.lng) return;

        // Color del marcador según tipo
        const color = inv.view_mode === 'investor' ? '#ffc107' : '#0d6efd';

        new maplibregl.Marker({ color: color })
            .setLngLat([inv.lng, inv.lat])
            .setPopup(new maplibregl.Popup().setHTML(`<b>${inv.title}</b><br>${inv.location_name}`))
            .addTo(mapInstance);
    });
}

function toggleFav(btn) {
    // Evitar que el click se propague al enlace de la tarjeta
    event.preventDefault();
    event.stopPropagation();

    const icon = btn.querySelector('i');
    if (icon.classList.contains('bi-heart')) {
        icon.classList.remove('bi-heart');
        icon.classList.add('bi-heart-fill', 'text-danger');
    } else {
        icon.classList.remove('bi-heart-fill', 'text-danger');
        icon.classList.add('bi-heart');
    }
}

// =============================
// SEARCH TABS LOGIC
// =============================
function switchSearchTab(btn, targetId) {
    // Update Tabs UI
    document.querySelectorAll('.search-tab').forEach(t => {
        t.classList.remove('active', 'text-dark');
        t.classList.add('text-muted');
        t.style.fontWeight = '500';
    });

    btn.classList.remove('text-muted');
    btn.classList.add('active', 'text-dark');
    btn.style.fontWeight = '700';

    // Update Fields Visibility
    document.querySelectorAll('.search-fields-group').forEach(g => g.classList.add('d-none'));
    const targetGroup = document.getElementById('fields-' + targetId);
    if (targetGroup) {
        targetGroup.classList.remove('d-none');

        // Optional: Add a small animation fade-in
        targetGroup.style.opacity = 0;
        setTimeout(() => {
            targetGroup.style.transition = 'opacity 0.3s ease';
            targetGroup.style.opacity = 1;
        }, 10);
    }

    // Update Hidden Input
    document.getElementById('searchCategory').value = targetId;
}

// =============================
// BLUE MODE LOGIC
// =============================
function setupBlueMode() {
    const toggle = document.getElementById('blueModeToggle');
    // If toggle doesn't exist (e.g. not logged in or not in navbar yet), check local storage
    if (localStorage.getItem('blueMode') === 'true') {
        document.body.classList.add('blue-mode');
        if (toggle) toggle.checked = true;
    }

    if (toggle) {
        toggle.addEventListener('change', (e) => {
            if (e.target.checked) {
                document.body.classList.add('blue-mode');
                localStorage.setItem('blueMode', 'true');
            } else {
                document.body.classList.remove('blue-mode');
                localStorage.setItem('blueMode', 'false');
            }
        });
    }
}
