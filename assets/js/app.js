// BandS Inversiones - Mapa + Listado (Versión PRO actualizada)
// Mapa con MapLibre, filtros sincronizados (desktop + mobile),
// listado tipo Airbnb y tarjetas con botones QR / Oculus.

// =============================
// VARIABLES GLOBALES
// =============================
let map;
let markersById = {};
let allInvestments = [];
let userLocation = null;

const filterState = {
  term: "",
  status: "",
  ticket: "",
  country: "",
  nearMe: false
};

const countryCenters = {
  ar: { lat: -34.6037, lng: -58.3816, zoom: 5 },
  mx: { lat: 19.4326, lng: -99.1332, zoom: 5 },
  cl: { lat: -33.4489, lng: -70.6693, zoom: 5 },
  es: { lat: 40.4168, lng: -3.7038, zoom: 5 },
  us: { lat: 37.0902, lng: -95.7129, zoom: 4 }
};

// =============================
// DOM READY
// =============================
document.addEventListener("DOMContentLoaded", () => {
  const mapContainer = document.getElementById("map");
  if (mapContainer) initMap();

  // Compactar navbar/footer al hacer scroll
  const navbar = document.querySelector(".bands-navbar");
  const footer = document.querySelector(".bands-footer");

  const onScroll = () => {
    const scrolled = window.scrollY > 10;
    if (navbar) navbar.classList.toggle("bands-navbar-compact", scrolled);
    if (footer) footer.classList.toggle("bands-footer-compact", scrolled);
  };

  window.addEventListener("scroll", onScroll);
  onScroll();
});

// =============================
// MAPA - MAPLIBRE GL
// =============================
function initMap() {
  if (map) return;

  const key = window.MAPTILER_KEY || "";
  const initialCenter = [-66.86, -29.41]; // La Rioja aprox [lng, lat]

  map = new maplibregl.Map({
    container: "map",
    style: `https://api.maptiler.com/maps/streets-v2/style.json?key=${key}`,
    center: initialCenter,
    zoom: 13
  });

  map.addControl(
    new maplibregl.NavigationControl({ showCompass: false }),
    "bottom-right"
  );

  map.on("load", () => {
    tryLocateUser();
    fetchInvestments();
  });
}

// =============================
// GEOLOCALIZACIÓN
// =============================
function tryLocateUser() {
  if (!navigator.geolocation) return;

  navigator.geolocation.getCurrentPosition(
    (pos) => {
      userLocation = {
        lat: pos.coords.latitude,
        lng: pos.coords.longitude
      };
      map.flyTo({
        center: [userLocation.lng, userLocation.lat],
        zoom: 14
      });
    },
    () => {
      // usuario negó permisos, seguimos normal
    },
    { enableHighAccuracy: true, timeout: 5000 }
  );
}

// =============================
// API - CARGAR INVERSIONES
// =============================
async function fetchInvestments() {
  try {
    const res = await fetch("api/investments.php");
    const data = await res.json();

    if (!Array.isArray(data)) {
      console.error("API no devolvió array:", data);
      allInvestments = [];
    } else {
      allInvestments = data;
    }

    syncControlsFromState();
    applyFiltersAndRender();
    wireFilters(); // engancha eventos de filtros

  } catch (err) {
    console.error("Error cargando inversiones:", err);
  }
}

// =============================
// TIERS DE INVERSIÓN
// =============================
function computeTier(minTicket) {
  const t = Number(minTicket || 0);
  if (t >= 40000) return "diamante";
  if (t >= 25000) return "oro";
  return "plata";
}

// =============================
// FILTROS (ESTADO + SINCRONIZACIÓN)
// =============================
function updateFilterState(patch) {
  const prevCountry = filterState.country;

  Object.assign(filterState, patch);

  // Si cambia país → centramos mapa
  if (patch.country !== undefined && patch.country !== prevCountry) {
    centerMapOnCountry(filterState.country);
  }

  // Si activan "cerca mío" sin ubicación aún → pedimos geolocalización
  if (patch.nearMe && !userLocation) {
    tryLocateUser();
  }

  syncControlsFromState();
  applyFiltersAndRender();
}

// reflect state -> inputs (desktop + mobile)
function syncControlsFromState() {
  const { term, status, ticket, country, nearMe } = filterState;

  // Desktop
  const termInput = document.getElementById("searchInput");
  const statusSel = document.getElementById("statusFilter");
  const ticketSel = document.getElementById("ticketFilter");
  const countrySel = document.getElementById("countrySelect");
  const nearMeBtn = document.getElementById("nearMeBtn");

  if (termInput && termInput.value !== term) termInput.value = term;
  if (statusSel && statusSel.value !== status) statusSel.value = status;
  if (ticketSel && ticketSel.value !== ticket) ticketSel.value = ticket;
  if (countrySel && countrySel.value !== country) countrySel.value = country;
  if (nearMeBtn) {
    nearMeBtn.classList.toggle("bands-pill-ghost-active", nearMe);
  }

  // Mobile (si existen)
  const termInputM = document.getElementById("searchInputMobile");
  const statusSelM = document.getElementById("statusFilterMobile");
  const ticketSelM = document.getElementById("ticketFilterMobile");
  const countrySelM = document.getElementById("countrySelectMobile");
  const nearMeBtnM = document.getElementById("nearMeBtnMobile");

  if (termInputM && termInputM.value !== term) termInputM.value = term;
  if (statusSelM && statusSelM.value !== status) statusSelM.value = status;
  if (ticketSelM && ticketSelM.value !== ticket) ticketSelM.value = ticket;
  if (countrySelM && countrySelM.value !== country) {
    countrySelM.value = country;
  }
  if (nearMeBtnM) {
    nearMeBtnM.classList.toggle("bands-pill-ghost-active", nearMe);
  }
}

function centerMapOnCountry(code) {
  if (!map || !code || !countryCenters[code]) return;
  const c = countryCenters[code];
  map.flyTo({ center: [c.lng, c.lat], zoom: c.zoom });
}

// enganchar eventos de filtros (una sola vez)
function wireFilters() {
  if (document.body.dataset.filtersWired === "1") return;
  document.body.dataset.filtersWired = "1";

  // Desktop
  const termInput = document.getElementById("searchInput");
  const statusSel = document.getElementById("statusFilter");
  const ticketSel = document.getElementById("ticketFilter");
  const countrySel = document.getElementById("countrySelect");
  const nearMeBtn = document.getElementById("nearMeBtn");
  const toggleFiltersBtn = document.getElementById("toggleFiltersBtn");
  const filtersRow = document.querySelector(".bands-filters-row");

  if (termInput) {
    termInput.addEventListener("input", (e) =>
      updateFilterState({ term: e.target.value })
    );
  }
  if (statusSel) {
    statusSel.addEventListener("change", (e) =>
      updateFilterState({ status: e.target.value })
    );
  }
  if (ticketSel) {
    ticketSel.addEventListener("change", (e) =>
      updateFilterState({ ticket: e.target.value })
    );
  }
  if (countrySel) {
    countrySel.addEventListener("change", (e) =>
      updateFilterState({ country: e.target.value })
    );
  }
  if (nearMeBtn) {
    nearMeBtn.addEventListener("click", () =>
      updateFilterState({ nearMe: !filterState.nearMe })
    );
  }

  // Compactar filtros (mostrar/ocultar fila)
  if (toggleFiltersBtn && filtersRow) {
    toggleFiltersBtn.addEventListener("click", () => {
      filtersRow.classList.toggle("d-none");
    });
  }

  // Mobile (si están definidos)
  const termInputM = document.getElementById("searchInputMobile");
  const statusSelM = document.getElementById("statusFilterMobile");
  const ticketSelM = document.getElementById("ticketFilterMobile");
  const countrySelM = document.getElementById("countrySelectMobile");
  const nearMeBtnM = document.getElementById("nearMeBtnMobile");

  if (termInputM) {
    termInputM.addEventListener("input", (e) =>
      updateFilterState({ term: e.target.value })
    );
  }
  if (statusSelM) {
    statusSelM.addEventListener("change", (e) =>
      updateFilterState({ status: e.target.value })
    );
  }
  if (ticketSelM) {
    ticketSelM.addEventListener("change", (e) =>
      updateFilterState({ ticket: e.target.value })
    );
  }
  if (countrySelM) {
    countrySelM.addEventListener("change", (e) =>
      updateFilterState({ country: e.target.value })
    );
  }
  if (nearMeBtnM) {
    nearMeBtnM.addEventListener("click", () =>
      updateFilterState({ nearMe: !filterState.nearMe })
    );
  }
}

// =============================
// APLICAR FILTROS Y RENDERIZAR
// =============================
function applyFiltersAndRender() {
  let { term, status, ticket, country, nearMe } = filterState;
  let list = [...allInvestments];

  // País (si en el futuro lo agregás a los datos)
  if (country) {
    // aquí podrías filtrar por país si tu API lo devuelve
  }

  // Texto
  if (term && term.trim() !== "") {
    const t = term.toLowerCase();
    list = list.filter(
      (inv) =>
        inv.title.toLowerCase().includes(t) ||
        inv.location_name.toLowerCase().includes(t)
    );
  }

  // Estado
  if (status) {
    list = list.filter((inv) => inv.status === status);
  }

  // Ticket mínimo
  if (ticket) {
    const tVal = parseInt(ticket, 10);
    if ([10000, 30000, 50000].includes(tVal)) {
      list = list.filter((inv) => (inv.min_ticket || 0) <= tVal);
    } else if (tVal === 50001) {
      list = list.filter((inv) => (inv.min_ticket || 0) > 50000);
    }
  }

  // Cerca mío
  if (nearMe && userLocation) {
    const maxKm = 20;
    list = list
      .map((inv) => {
        const dist = haversineKm(
          userLocation.lat,
          userLocation.lng,
          inv.lat,
          inv.lng
        );
        return { ...inv, _dist: dist };
      })
      .filter((inv) => inv._dist <= maxKm)
      .sort((a, b) => a._dist - b._dist);
  }

  renderKpis(allInvestments);
  renderMarkers(list);
  renderInvestmentsList(list);
  renderInvestmentsListMobile(list);
}

// =============================
// KPI TIPO "BOLSA"
// =============================
function renderKpis(list) {
  const total = list.length;
  const construction = list.filter((i) => i.status === "construction").length;
  const finished = list.filter((i) => i.status === "finished").length;

  const elP = document.getElementById("kpiProjects");
  const elC = document.getElementById("kpiConstruction");
  const elF = document.getElementById("kpiFinished");

  if (elP) elP.textContent = total;
  if (elC) elC.textContent = construction;
  if (elF) elF.textContent = finished;
}

// =============================
// MAP MARKERS
// =============================
function clearMarkers() {
  Object.values(markersById).forEach((m) => m.remove());
  markersById = {};
}

function renderMarkers(list) {
  if (!map) return;
  clearMarkers();

  list.forEach((inv) => {
    const progress = Number(inv.progress_percent || 0);
    const img =
      inv.image_url ||
      "https://images.pexels.com/photos/323780/pexels-photo-323780.jpeg?auto=compress&cs=tinysrgb&w=800";

    const popupHtml = `
      <div class="bands-popup-img-wrapper">
        <img class="bands-popup-img" src="${img}" alt="${escapeHtml(
      inv.title
    )}">
      </div>

      <strong>${escapeHtml(inv.title)}</strong><br>
      <small>${escapeHtml(inv.location_name)}</small><br>

      <div class="mt-1 mb-1">
        <div class="bands-progress-bar">
          <div class="bands-progress-fill" style="width:${progress}%"></div>
        </div>
        <small>${progress}% avance</small>
      </div>
    `;

    const el = document.createElement("div");
    el.className = "bands-map-marker";

    const marker = new maplibregl.Marker(el)
      .setLngLat([inv.lng, inv.lat])
      .setPopup(new maplibregl.Popup({ offset: 16 }).setHTML(popupHtml))
      .addTo(map);

    markersById[inv.id] = marker;
  });
}

// =============================
// LISTADOS (DESKTOP + MOBILE)
// =============================
function renderInvestmentsList(list) {
  const container = document.getElementById("investmentsList");
  if (!container) return;

  container.innerHTML = "";

  if (!list.length) {
    container.innerHTML =
      '<p class="text-muted small mb-0">No hay inversiones.</p>';
    return;
  }

  list.forEach((inv) => container.appendChild(createInvestmentCard(inv)));

  wireFavorites();
}

function renderInvestmentsListMobile(list) {
  const container = document.getElementById("investmentsListMobile");
  if (!container) return;

  container.innerHTML = "";

  if (!list.length) {
    container.innerHTML =
      '<p class="text-muted small mb-0">No hay inversiones.</p>';
    return;
  }

  list.forEach((inv) => container.appendChild(createInvestmentCard(inv)));

  wireFavorites();
}

// =============================
// CARD DE INVERSIÓN (PRO)
// =============================
// Aquí actualizamos para:
// - Mostrar descripción completa (sin truncar)
// - Botones centrados: QR y Oculus
function createInvestmentCard(inv) {
  const card = document.createElement("article");
  card.className = "bands-card bands-card-inv mb-3";
  card.dataset.invId = inv.id;

  const progress = Number(inv.progress_percent || 0);
  const img =
    inv.image_url ||
    "https://images.pexels.com/photos/323780/pexels-photo-323780.jpeg?auto=compress&cs=tinysrgb&w=800";
  const tier = computeTier(inv.min_ticket);
  const favClass = inv.is_favorite == 1 ? "bands-fav-active" : "";

  card.innerHTML = `
    <div class="bands-card-img-wrapper">
      <img src="${img}" class="bands-card-img" alt="${escapeHtml(inv.title)}">
      <button type="button" class="bands-fav-btn ${favClass}" data-id="${inv.id}">♥</button>
    </div>

    <div class="p-2">
      <div class="d-flex justify-content-between align-items-center mb-1">
        <span class="badge bands-tier-${tier} text-uppercase small">${tier}</span>
        <span class="small text-muted">
          ${inv.min_ticket
            ? "USD " + Number(inv.min_ticket).toLocaleString()
            : ""}
        </span>
      </div>

      <h3 class="h6 mb-1">${escapeHtml(inv.title)}</h3>
      <p class="mb-1 text-muted small">${escapeHtml(inv.location_name)}</p>

      <!-- DESCRIPCIÓN COMPLETA (sin truncar) -->
      <p class="mb-2 small">${escapeHtml(inv.description || "")}</p>

      <div class="bands-progress-bar mb-1">
        <div class="bands-progress-fill" style="width:${progress}%"></div>
      </div>

      <div class="d-flex justify-content-between small text-muted mb-2">
        <span>${progress}% avance · ${inv.status}</span>
        <span>${
          tier === "diamante"
            ? "Ticket alto"
            : tier === "oro"
            ? "Ticket medio"
            : "Accesible"
        }</span>
      </div>

      <!-- BOTONES QR / OCULUS CENTRADOS -->
      <div class="d-flex justify-content-center gap-2">
        <a href="qr.php?id=${encodeURIComponent(
          inv.id
        )}" class="btn btn-sm btn-outline-secondary rounded-pill bands-card-action-btn"
           onclick="event.stopPropagation()">
          <i class="bi bi-qr-code me-1"></i> QR
        </a>
        <a href="oculus.php?id=${encodeURIComponent(
          inv.id
        )}" class="btn btn-sm btn-outline-secondary rounded-pill bands-card-action-btn"
           onclick="event.stopPropagation()">
          <i class="bi bi-box me-1"></i> Oculus
        </a>
      </div>
    </div>
  `;

  // Click en la tarjeta → enfocar marcador en el mapa
  card.addEventListener("click", (e) => {
    if (e.target.closest(".bands-fav-btn")) return; // si clic en favorito, no mover mapa
    focusMarker(inv);
  });

  return card;
}

function focusMarker(inv) {
  if (!map) return;
  map.flyTo({ center: [inv.lng, inv.lat], zoom: 15 });

  const m = markersById[inv.id];
  if (m) m.getPopup().addTo(map);
}

// =============================
// FAVORITOS
// =============================
function wireFavorites() {
  document.querySelectorAll(".bands-fav-btn").forEach((btn) => {
    btn.addEventListener("click", async (e) => {
      e.stopPropagation();
      btn.classList.toggle("bands-fav-active");

      // Si en algún momento conectamos con toggle_favorite.php,
      // acá se puede hacer el fetch POST.
    });
  });
}

// =============================
// UTILIDADES
// =============================
function escapeHtml(str) {
  if (!str) return "";
  return String(str)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;");
}

function haversineKm(lat1, lon1, lat2, lon2) {
  const R = 6371;
  const dLat = ((lat2 - lat1) * Math.PI) / 180;
  const dLon = ((lon2 - lon1) * Math.PI) / 180;
  const a =
    Math.sin(dLat / 2) ** 2 +
    Math.cos((lat1 * Math.PI) / 180) *
      Math.cos((lat2 * Math.PI) / 180) *
      Math.sin(dLon / 2) ** 2;

  return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}
