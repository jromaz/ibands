<?php
// includes/navbar_landing.php
require_once __DIR__ . '/auth.php';
$user = current_user();
?>
<nav class="navbar navbar-expand-lg bands-navbar fixed-top bg-white border-bottom">
  <div class="container-fluid px-4 px-md-5">

    <!-- Brand -->
    <a class="navbar-brand d-flex align-items-center" href="landing.php">
      <img src="assets/img/BandS.png"
           alt="BandS Inversiones"
           class="me-2"
           style="height: 32px; width:auto;">
      <span class="d-none d-lg-inline fw-bold text-danger">BandS</span>
    </a>

    <!-- Search Bar (Removed as per request, moving to body) -->
    <div class="d-none d-md-flex flex-grow-1 justify-content-center">
      <!-- Espacio vacío o reservado si se quiere centrar algo luego -->
    </div>

    <!-- User Menu -->
    <div class="d-flex align-items-center gap-2">
      <?php if ($user): ?>
        <!-- Logged in: Show Name/Avatar -->
        <div class="dropdown">
          <button class="btn border rounded-pill d-flex align-items-center gap-2 p-1 ps-3 shadow-sm bg-white" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="fw-semibold small"><?= htmlspecialchars($user['name']) ?></span>
            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center overflow-hidden" style="width: 30px; height: 30px;">
              <i class="bi bi-person-fill"></i>
            </div>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2 p-2" style="min-width: 240px;">
            <li><h6 class="dropdown-header">Hola, <?= htmlspecialchars($user['name']) ?></h6></li>
            <li><a class="dropdown-item fw-semibold" href="dashboard.php">Panel</a></li>
            <li><a class="dropdown-item" href="index.php">Mapa</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
          </ul>
        </div>
      <?php else: ?>
        <!-- Not logged in: Just the dropdown icon or Login button -->
        <div class="dropdown">
          <button class="btn border rounded-pill d-flex align-items-center gap-2 p-1 ps-2 pe-2 shadow-sm bg-white" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-list fs-5"></i>
            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center overflow-hidden" style="width: 30px; height: 30px;">
              <i class="bi bi-person-fill"></i>
            </div>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2 p-2" style="min-width: 240px;">
            <li><a class="dropdown-item fw-semibold" href="login.php">Regístrate</a></li>
            <li><a class="dropdown-item" href="login.php">Inicia sesión</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Ayuda</a></li>
          </ul>
        </div>
      <?php endif; ?>
    </div>

  </div>
  
  <!-- Mobile Search (Visible only on small screens) -->
  <div class="container-fluid d-md-none mt-2">
    <div class="bands-search-bar w-100 shadow-sm border rounded-pill px-3 py-2 d-flex align-items-center gap-3">
      <i class="bi bi-search text-dark"></i>
      <div class="d-flex flex-column">
        <span class="fw-semibold small">¿A dónde quieres ir?</span>
        <span class="text-muted x-small" style="font-size: 0.75rem;">Cualquier lugar · Cualquier semana · ¿Cuántos?</span>
      </div>
    </div>
  </div>
</nav>
