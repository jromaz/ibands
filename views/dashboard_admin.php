<?php
// views/dashboard_admin.php
?>
<section class="bands-dashboard bands-dashboard-admin">
  <header class="mb-3">
    <h1 class="h5 mb-1">Administración de propiedades e inversiones</h1>
    <p class="small text-muted mb-0">
      Gestioná listings, usuarios y tipos de carga según la complejidad del producto inmobiliario.
    </p>
  </header>

  <div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
      <div class="bands-card p-2 text-center">
        <div class="small text-muted">Publicadas</div>
        <div class="fw-bold" id="adminKpiPublished">0</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="bands-card p-2 text-center">
        <div class="small text-muted">Pausadas</div>
        <div class="fw-bold" id="adminKpiPaused">0</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="bands-card p-2 text-center">
        <div class="small text-muted">Inversión</div>
        <div class="fw-bold" id="adminKpiInvest">0</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="bands-card p-2 text-center">
        <div class="small text-muted">Venta/Alquiler</div>
        <div class="fw-bold" id="adminKpiMarket">0</div>
      </div>
    </div>
  </div>

  <ul class="nav nav-pills mb-3" id="adminLoadModes" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="simple-tab" data-bs-toggle="pill"
              data-bs-target="#simplePanel" type="button" role="tab">
        Carga simple
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="full-tab" data-bs-toggle="pill"
              data-bs-target="#fullPanel" type="button" role="tab">
        Carga exhaustiva
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="temp-tab" data-bs-toggle="pill"
              data-bs-target="#tempPanel" type="button" role="tab">
        Alquiler temporal
      </button>
    </li>
  </ul>

  <div class="tab-content" id="adminLoadModesContent">
    <!-- CARGA SIMPLE -->
    <div class="tab-pane fade show active" id="simplePanel" role="tabpanel">
      <div class="bands-card p-3 mb-3">
        <h2 class="h6 mb-2">Carga simple</h2>
        <p class="small text-muted">Ideal para un alta rápida con datos mínimos.</p>
        <form id="adminSimpleForm" class="row g-2">
          <div class="col-md-4">
            <label class="form-label small">Título</label>
            <input type="text" name="title" class="form-control form-control-sm" required>
          </div>
          <div class="col-md-3">
            <label class="form-label small">Operación</label>
            <select name="operation_type" class="form-select form-select-sm" required>
              <option value="inversion">Inversión</option>
              <option value="venta">Venta</option>
              <option value="alquiler">Alquiler</option>
              <option value="alquiler_temporal">Alquiler temporal</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label small">Tipo</label>
            <select name="asset_type" class="form-select form-select-sm">
              <option value="">Seleccionar</option>
              <option value="departamento">Departamento</option>
              <option value="casa">Casa</option>
              <option value="lote">Lote</option>
              <option value="comercial">Comercial</option>
            </select>
          </div>
          <div class="col-md-2">
            <label class="form-label small">Precio / Ticket</label>
            <input type="number" name="price_total" class="form-control form-control-sm" min="0" step="1000">
          </div>

          <div class="col-md-6">
            <label class="form-label small">Ubicación</label>
            <input type="text" name="location_name" class="form-control form-control-sm">
          </div>
          <div class="col-md-3">
            <label class="form-label small">Lat</label>
            <input type="number" name="lat" class="form-control form-control-sm" step="0.000001">
          </div>
          <div class="col-md-3">
            <label class="form-label small">Lng</label>
            <input type="number" name="lng" class="form-control form-control-sm" step="0.000001">
          </div>

          <div class="col-12">
            <label class="form-label small">Descripción</label>
            <textarea name="description" rows="2" class="form-control form-control-sm"></textarea>
          </div>

          <div class="col-12 d-flex justify-content-end">
            <button type="submit" class="btn btn-sm btn-primary">
              Guardar
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- CARGA EXHAUSTIVA -->
    <div class="tab-pane fade" id="fullPanel" role="tabpanel">
      <div class="bands-card p-3 mb-3">
        <h2 class="h6 mb-2">Carga exhaustiva</h2>
        <p class="small text-muted">Incluye todas las características detalladas.</p>
        <!-- Aquí podés expandir con más campos -->
        <p class="small text-muted">
          (Pendiente de completar con todos los campos que definas en el modelo
          de negocio: m², amenities, cocheras, etc.)
        </p>
      </div>
    </div>

    <!-- CARGA TEMPORAL -->
    <div class="tab-pane fade" id="tempPanel" role="tabpanel">
      <div class="bands-card p-3 mb-3">
        <h2 class="h6 mb-2">Alquiler temporal</h2>
        <p class="small text-muted">Pensado para alquiler tipo Airbnb: por noche, estadía mínima, etc.</p>
        <!-- Espacio para configuración específica de alquiler temporal -->
        <p class="small text-muted">
          (Pendiente de completar: precio por noche, mínimo de noches, fechas disponibles, etc.)
        </p>
      </div>
    </div>
  </div>
</section>
