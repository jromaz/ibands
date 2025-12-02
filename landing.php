<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

// Page title
$pageTitle = "Inicio";
?>
<?php require __DIR__ . '/includes/header.php'; ?>

<!-- Custom Navbar for Landing Page -->
<?php require __DIR__ . '/includes/navbar_landing.php'; ?>

<main class="bands-main pt-5 mt-3">
  
  <!-- Categories Bar -->
  <!-- Search & Categories Section -->
  <div class="container px-4 px-md-5 mb-5">
    <div class="bg-white rounded-5 shadow-lg p-4 mx-auto border" style="max-width: 900px; margin-top: -20px; position: relative; z-index: 10;">
      
      <!-- Search Header / Tabs -->
      <div class="d-flex justify-content-center gap-3 gap-md-4 mb-4 pb-2">
        <button class="btn btn-link text-decoration-none text-dark fw-bold rounded-0 search-tab active" data-target="lotes" onclick="switchSearchTab(this, 'lotes')">
          Lotes
        </button>
        <button class="btn btn-link text-decoration-none text-muted fw-semibold rounded-0 search-tab" data-target="venta" onclick="switchSearchTab(this, 'venta')">
          Venta
        </button>
        <button class="btn btn-link text-decoration-none text-muted fw-semibold rounded-0 search-tab" data-target="alquiler" onclick="switchSearchTab(this, 'alquiler')">
          Alquiler
        </button>
        <button class="btn btn-link text-decoration-none text-muted fw-semibold rounded-0 search-tab" data-target="temporal" onclick="switchSearchTab(this, 'temporal')">
          Alquiler Temporal
        </button>
      </div>

      <!-- Search Form (Airbnb Style Pill) -->
      <form action="index.php" method="GET" class="bands-search-form position-relative">
        <input type="hidden" name="category" id="searchCategory" value="lotes">

        <!-- Main Pill Container -->
        <div class="bands-search-pill shadow-lg border d-flex align-items-center">
          
          <!-- Dynamic Fields Container -->
          <div class="flex-grow-1 d-flex align-items-center h-100">
            
            <!-- LOTES Fields -->
            <div id="fields-lotes" class="search-fields-group w-100 d-flex align-items-center">
              
              <!-- Field 1: Zona -->
              <div class="bands-search-field flex-grow-1 ps-4 pe-3 py-2 position-relative cursor-pointer hover-bg-gray rounded-pill-start">
                <label class="d-block small fw-bold text-dark mb-0">Ubicación</label>
                <input type="text" class="form-control p-0 border-0 bg-transparent text-muted small text-truncate" name="lotes_zona" placeholder="Explorar destinos">
              </div>
              
              <div class="bands-search-divider"></div>

              <!-- Field 2: Superficie -->
              <div class="bands-search-field px-3 py-2 position-relative cursor-pointer hover-bg-gray" style="width: 20%;">
                <label class="d-block small fw-bold text-dark mb-0">Superficie</label>
                <input type="number" class="form-control p-0 border-0 bg-transparent text-muted small" name="lotes_m2" placeholder="Mínimo m²">
              </div>

              <div class="bands-search-divider"></div>

              <!-- Field 3: Precio -->
              <div class="bands-search-field px-3 py-2 position-relative cursor-pointer hover-bg-gray" style="width: 20%;">
                <label class="d-block small fw-bold text-dark mb-0">Presupuesto</label>
                <input type="number" class="form-control p-0 border-0 bg-transparent text-muted small" name="lotes_precio" placeholder="Máximo USD">
              </div>

              <div class="bands-search-divider"></div>

              <!-- Field 4: Financiación -->
              <div class="bands-search-field px-3 py-2 position-relative cursor-pointer hover-bg-gray" style="width: 20%;">
                <label class="d-block small fw-bold text-dark mb-0">Financiación</label>
                <div class="form-check p-0 min-h-input d-flex align-items-center">
                   <input class="form-check-input m-0 me-2" type="checkbox" name="lotes_financiacion" id="lotesFinanciacion">
                   <label class="form-check-label text-muted small text-truncate" for="lotesFinanciacion">Sí, buscar</label>
                </div>
              </div>

            </div>

            <!-- VENTA Fields -->
            <div id="fields-venta" class="search-fields-group w-100 d-flex align-items-center d-none">
              
              <div class="bands-search-field flex-grow-1 ps-4 pe-3 py-2 position-relative cursor-pointer hover-bg-gray rounded-pill-start">
                <label class="d-block small fw-bold text-dark mb-0">Ubicación</label>
                <input type="text" class="form-control p-0 border-0 bg-transparent text-muted small text-truncate" name="venta_zona" placeholder="Barrio o Ciudad">
              </div>
              
              <div class="bands-search-divider"></div>

              <div class="bands-search-field px-3 py-2 position-relative cursor-pointer hover-bg-gray" style="width: 18%;">
                <label class="d-block small fw-bold text-dark mb-0">Ambientes</label>
                <select class="form-select p-0 border-0 bg-transparent text-muted small shadow-none" name="venta_ambientes">
                  <option value="">Cualquiera</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4+">4+</option>
                </select>
              </div>

              <div class="bands-search-divider"></div>

              <div class="bands-search-field px-3 py-2 position-relative cursor-pointer hover-bg-gray" style="width: 20%;">
                <label class="d-block small fw-bold text-dark mb-0">Superficie</label>
                <input type="number" class="form-control p-0 border-0 bg-transparent text-muted small" name="venta_superficie" placeholder="m² cubiertos">
              </div>

              <div class="bands-search-divider"></div>

              <div class="bands-search-field px-3 py-2 position-relative cursor-pointer hover-bg-gray" style="width: 22%;">
                <label class="d-block small fw-bold text-dark mb-0">Crédito</label>
                <div class="form-check p-0 min-h-input d-flex align-items-center">
                   <input class="form-check-input m-0 me-2" type="checkbox" name="venta_credito" id="ventaCredito">
                   <label class="form-check-label text-muted small text-truncate" for="ventaCredito">Apto Crédito</label>
                </div>
              </div>

            </div>

            <!-- ALQUILER Fields -->
            <div id="fields-alquiler" class="search-fields-group w-100 d-flex align-items-center d-none">
              
              <div class="bands-search-field flex-grow-1 ps-4 pe-3 py-2 position-relative cursor-pointer hover-bg-gray rounded-pill-start">
                <label class="d-block small fw-bold text-dark mb-0">Ubicación</label>
                <input type="text" class="form-control p-0 border-0 bg-transparent text-muted small text-truncate" name="alquiler_zona" placeholder="Zona deseada">
              </div>
              
              <div class="bands-search-divider"></div>

              <div class="bands-search-field px-3 py-2 position-relative cursor-pointer hover-bg-gray" style="width: 20%;">
                <label class="d-block small fw-bold text-dark mb-0">Precio</label>
                <input type="number" class="form-control p-0 border-0 bg-transparent text-muted small" name="alquiler_precio" placeholder="Máximo $">
              </div>

              <div class="bands-search-divider"></div>

              <div class="bands-search-field px-3 py-2 position-relative cursor-pointer hover-bg-gray" style="width: 18%;">
                <label class="d-block small fw-bold text-dark mb-0">Ambientes</label>
                <select class="form-select p-0 border-0 bg-transparent text-muted small shadow-none" name="alquiler_ambientes">
                  <option value="">Todos</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3+</option>
                </select>
              </div>

              <div class="bands-search-divider"></div>

              <div class="bands-search-field px-3 py-2 position-relative cursor-pointer hover-bg-gray" style="width: 22%;">
                <label class="d-block small fw-bold text-dark mb-0">Compra</label>
                <div class="form-check p-0 min-h-input d-flex align-items-center">
                   <input class="form-check-input m-0 me-2" type="checkbox" name="alquiler_opcion_compra" id="alquilerCompra">
                   <label class="form-check-label text-muted small text-truncate" for="alquilerCompra">Opción Compra</label>
                </div>
              </div>

            </div>

            <!-- ALQUILER TEMPORAL Fields -->
            <div id="fields-temporal" class="search-fields-group w-100 d-flex align-items-center d-none">
              
              <div class="bands-search-field flex-grow-1 ps-4 pe-3 py-2 position-relative cursor-pointer hover-bg-gray rounded-pill-start">
                <label class="d-block small fw-bold text-dark mb-0">Destino</label>
                <input type="text" class="form-control p-0 border-0 bg-transparent text-muted small text-truncate" name="temporal_zona" placeholder="¿A dónde vas?">
              </div>
              
              <div class="bands-search-divider"></div>

              <div class="bands-search-field px-3 py-2 position-relative cursor-pointer hover-bg-gray" style="width: 25%;">
                <label class="d-block small fw-bold text-dark mb-0">Llegada</label>
                <input type="text" class="form-control p-0 border-0 bg-transparent text-muted small" name="temporal_checkin" placeholder="Agrega fechas" onfocus="(this.type='date')" onblur="(this.type='text')">
              </div>

              <div class="bands-search-divider"></div>

              <div class="bands-search-field px-3 py-2 position-relative cursor-pointer hover-bg-gray" style="width: 25%;">
                <label class="d-block small fw-bold text-dark mb-0">Salida</label>
                <input type="text" class="form-control p-0 border-0 bg-transparent text-muted small" name="temporal_checkout" placeholder="Agrega fechas" onfocus="(this.type='date')" onblur="(this.type='text')">
              </div>

              <div class="bands-search-divider"></div>

              <div class="bands-search-field px-3 py-2 position-relative cursor-pointer hover-bg-gray" style="width: 20%;">
                <label class="d-block small fw-bold text-dark mb-0">Viajeros</label>
                <input type="number" class="form-control p-0 border-0 bg-transparent text-muted small" name="temporal_huespedes" placeholder="¿Cuántos?">
              </div>

            </div>

          </div>

          <!-- Search Button (Circle) -->
          <div class="pe-2">
            <button type="submit" class="btn btn-danger rounded-circle p-0 d-flex align-items-center justify-content-center shadow-sm hover-scale" style="width: 48px; height: 48px;">
              <i class="bi bi-search fs-5"></i>
            </button>
          </div>

        </div>
      </form>
    </div>
  </div>

  <!-- Listings Grid -->
  <div class="container-fluid px-4 px-md-5">
    <div id="investmentsList" class="bands-investments-list bands-investments-grid-landing pb-5">
      <!-- Populated by JS -->
      <div class="text-center w-100 py-5">
        <div class="spinner-border text-bands" role="status">
          <span class="visually-hidden">Cargando...</span>
        </div>
      </div>
    </div>
  </div>

</main>

<!-- Toggle Map Button (Floating) -->
<div class="fixed-bottom d-flex justify-content-center pb-4 pointer-events-none">
  <a href="index.php" class="btn btn-dark rounded-pill shadow-lg px-4 py-2 pointer-events-auto d-flex align-items-center gap-2" style="z-index: 1050;">
    <span>Mostrar mapa</span>
    <i class="bi bi-map-fill"></i>
  </a>
</div>


<?php require __DIR__ . '/includes/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", () => {
  // Manually trigger fetchInvestments since we don't have a map to trigger it via initMap
  if (typeof fetchInvestments === 'function') {
    fetchInvestments();
  }
});

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
</script>

