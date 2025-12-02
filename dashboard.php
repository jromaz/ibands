<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
?>
<?php require __DIR__ . '/includes/header.php'; ?>
<?php require __DIR__ . '/includes/navbar.php'; ?>

<main class="bands-main">
  <div class="container-fluid py-3">
    <div id="bands-app-root">
      <!-- Aquí se inyectan dinámicamente las vistas según el rol -->
      <div class="text-center py-5">
        <div class="spinner-border text-warning" role="status">
          <span class="visually-hidden">Cargando...</span>
        </div>
        <p class="small text-muted mt-2 mb-0">Preparando tu panel BandS...</p>
      </div>
    </div>
  </div>
</main>

<!-- Librería de gráficos para el perfil inversor -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- JS del router de vistas y lógica de roles -->
<script src="assets/js/dashboard.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
