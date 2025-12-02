<?php
// views/dashboard_inversor.php
?>
<section class="bands-dashboard bands-dashboard-inversor">
  <header class="mb-3">
    <h1 class="h5 mb-1">Tu portfolio de inversión</h1>
    <p class="small text-muted mb-0">
      Visualizá tus posiciones, ROI y evolución de cada proyecto.
    </p>
  </header>

  <div id="investorSummary" class="row g-3 mb-3">
    <!-- KPI cards llenados por JS -->
  </div>

  <div class="bands-card p-3 mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h2 class="h6 mb-0">ROI por proyecto</h2>
      <span class="small text-muted">Vista resumida · Datos en tiempo real</span>
    </div>
    <canvas id="investorRoiChart" height="150"></canvas>
  </div>

  <div class="bands-card p-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h2 class="h6 mb-0">Detalle de posiciones</h2>
      <span class="small text-muted">Montos, situación y avance de obra</span>
    </div>
    <div class="table-responsive">
      <table class="table table-sm align-middle mb-0" id="investorPositionsTable">
        <thead class="table-light">
          <tr>
            <th>Proyecto</th>
            <th class="text-end">Invertido (USD)</th>
            <th class="text-end">ROI (%)</th>
            <th class="text-center">Estado</th>
            <th class="text-center">Avance obra</th>
          </tr>
        </thead>
        <tbody>
          <!-- JS -->
        </tbody>
      </table>
    </div>
  </div>
</section>
