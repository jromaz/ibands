<?php
// views/dashboard_inmobiliaria.php
// Panel exclusivo para cargar propiedades
?>
<section class="bands-dashboard bands-dashboard-inmobiliaria">
  <div class="row mb-4">
    <div class="col-12">
      <div class="card border-0 shadow-sm bg-primary text-white p-4 rounded-4">
        <h1 class="h4 fw-bold mb-1">Panel de Gestión Inmobiliaria</h1>
        <p class="mb-0 opacity-75">Carga tus propiedades, gestiona visitas y visualiza métricas.</p>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <!-- FORMULARIO DE CARGA -->
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
          <h2 class="h5 fw-bold mb-0">Publicar Nueva Propiedad</h2>
        </div>
        <div class="card-body p-4">
          
          <form id="propertyForm" onsubmit="submitProperty(event)">
            <!-- Fila 1 -->
            <div class="row g-3 mb-3">
              <div class="col-md-8">
                <label class="form-label small fw-bold">Título del Anuncio</label>
                <input type="text" name="title" class="form-control" placeholder="Ej: Casa moderna en Zona Norte" required minlength="10">
                <div class="invalid-feedback">El título debe tener al menos 10 caracteres.</div>
              </div>
              <div class="col-md-4">
                <label class="form-label small fw-bold">Tipo</label>
                <select name="asset_type" class="form-select">
                  <option value="casa">Casa</option>
                  <option value="departamento">Departamento</option>
                  <option value="lote">Lote</option>
                  <option value="comercial">Comercial</option>
                </select>
              </div>
            </div>

            <!-- Fila 2 -->
            <div class="row g-3 mb-3">
              <div class="col-md-4">
                <label class="form-label small fw-bold">Operación</label>
                <select name="operation_type" class="form-select">
                  <option value="venta">Venta</option>
                  <option value="alquiler">Alquiler</option>
                  <option value="alquiler_temporal">Temporal</option>
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label small fw-bold">Precio (USD)</label>
                <input type="number" name="price_total" class="form-control" placeholder="0.00" required min="1">
              </div>
              <div class="col-md-4">
                <label class="form-label small fw-bold">Superficie (m²)</label>
                <input type="number" name="surface_total" class="form-control" placeholder="0" required>
              </div>
            </div>

            <!-- Fila 3: Ubicación -->
            <div class="mb-3">
              <label class="form-label small fw-bold">Ubicación (Dirección aproximada)</label>
              <input type="text" name="location_name" class="form-control" placeholder="Ej: Palermo Hollywood, CABA" required>
            </div>
            
            <!-- Lat/Lng manuales (idealmente usaríamos un selector de mapa) -->
            <div class="row g-3 mb-3">
               <div class="col-6">
                   <input type="text" name="lat" class="form-control form-control-sm" placeholder="Latitud (ej: -34.6)" required>
               </div>
               <div class="col-6">
                   <input type="text" name="lng" class="form-control form-control-sm" placeholder="Longitud (ej: -58.4)" required>
               </div>
            </div>

            <div class="mb-3">
              <label class="form-label small fw-bold">Descripción</label>
              <textarea name="description" class="form-control" rows="4" placeholder="Describe los detalles..." required></textarea>
            </div>

            <div class="text-end">
              <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold">
                <i class="bi bi-cloud-upload me-2"></i>Publicar
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>

    <!-- SIDEBAR MÉTRICAS -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-4">
          <h6 class="fw-bold mb-3">Tus Publicaciones</h6>
          <div class="d-flex justify-content-between mb-2">
            <span>Activas</span>
            <span class="fw-bold text-success">12</span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span>En revisión</span>
            <span class="fw-bold text-warning">2</span>
          </div>
          <div class="d-flex justify-content-between">
            <span>Visitas totales</span>
            <span class="fw-bold">1,402</span>
          </div>
        </div>
      </div>
      
      <div class="alert alert-info border-0 rounded-4 small">
        <i class="bi bi-info-circle-fill me-2"></i>
        Recuerda que como <strong>Inmobiliaria</strong> no tienes acceso a los datos financieros de los proyectos de inversión de terceros.
      </div>
    </div>
  </div>
</section>

<!-- Cargar script de validación -->
<script src="assets/js/validation.js"></script>
<script>
async function submitProperty(e) {
    e.preventDefault();
    const form = e.target;
    
    // Validación frontend
    if (!validateForm(form)) return;

    const formData = new FormData(form);
    
    try {
        const res = await fetch('api/save_property.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();
        
        if(res.ok) {
            alert('Propiedad creada exitosamente');
            form.reset();
        } else {
            alert('Error: ' + data.error);
        }
    } catch(err) {
        console.error(err);
        alert('Error de conexión');
    }
}
</script>