<?php
// includes/navbar.php
require_once __DIR__ . '/auth.php';
$user = current_user();
$roleLabel = null;

if ($user && !empty($user['role_name'])) {
    switch (strtolower($user['role_name'])) {
        case 'inversor':
            $roleLabel = 'Inversor';
            break;
        case 'admin_propiedades':
            $roleLabel = 'Admin Propiedades';
            break;
        default:
            $roleLabel = ucfirst($user['role_name']);
    }
}
?>
<nav class="navbar navbar-expand-lg bands-navbar fixed-top">
  <div class="container-fluid px-3">

    <!-- Brand -->
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="assets/img/BandS.png"
           alt="BandS Inversiones"
           class="me-2"
           style="height: 34px; width:auto;">
      <span class="d-none d-sm-inline fw-semibold">BandS Inversiones</span>
    </a>

    <!-- Toggler -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#bandsNavbar" aria-controls="bandsNavbar"
            aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Links -->
    <div class="collapse navbar-collapse" id="bandsNavbar">

      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="index.php">
            <i class="bi bi-map me-1"></i>Mapa de oportunidades
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">
            <i class="bi bi-grid me-1"></i>Panel
          </a>
        </li>
      </ul>

      <!-- Usuario -->
      <div class="d-flex align-items-center gap-2">
        <?php if ($user): ?>
          <div class="text-end me-2 d-none d-sm-block">
            <div class="small text-muted">Sesión activa</div>
            <div class="small fw-semibold">
              <?php echo htmlspecialchars($user['name'] ?? $user['email'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
          </div>
          <?php if ($roleLabel): ?>
            <span class="badge rounded-pill text-bg-secondary small">
              <?php echo htmlspecialchars($roleLabel, ENT_QUOTES, 'UTF-8'); ?>
            </span>
          <?php endif; ?>

          <a href="logout.php" class="btn btn-sm btn-outline-light ms-2">
            <i class="bi bi-box-arrow-right me-1"></i>Salir
          </a>
        <?php else: ?>
          <a href="login.php" class="btn btn-sm btn-outline-light">
            <i class="bi bi-person me-1"></i>Ingresar
          </a>
          <a href="register.php" class="btn btn-sm btn-warning ms-1">
            <i class="bi bi-star me-1"></i>Crear cuenta
          </a>
        <?php endif; ?>
      </div>

    </div>
  </div>
</nav>
