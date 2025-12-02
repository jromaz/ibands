<?php
require_once __DIR__ . '/auth.php';
?>
<footer class="bands-footer mt-4">
  <div class="container py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
    <div>
      <div class="small text-uppercase text-muted mb-1">
        BandS Inversiones
      </div>
      <div class="fw-semibold">
        Mapeamos oportunidades para que decidas con calma.
      </div>
    </div>
    <div class="d-flex flex-wrap gap-2">
      <a href="#mapa" class="btn btn-sm bands-btn-outline-footer">
        Ver mapa de oportunidades
      </a>
      <button class="btn btn-sm bands-btn-footer" data-bs-toggle="offcanvas" data-bs-target="#listOffcanvas">
        Gestionar inversiones
      </button>
    </div>
  </div>
</footer>

<!-- Botón flotante de WhatsApp -->
<a href="https://wa.me/5493800000000"
   class="bands-whatsapp-fab" target="_blank" rel="noopener">
  <i class="bi bi-whatsapp"></i>
</a>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  window.BASE_URL      = "<?= rtrim(BASE_URL, '/') ?>/";
  window.currentUserId = <?= isset($_SESSION['user']) ? (int)$_SESSION['user']['id'] : 'null' ?>;

  // 🔑 Clave para MapTiler / MapLibre
  window.MAPTILER_KEY  = "i9tHAYLVopv8PNjMHFYs";
</script>

<script src="assets/js/app.js"></script>
</body>
</html>
