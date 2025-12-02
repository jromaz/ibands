<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
?>
<?php require __DIR__ . '/includes/header.php'; ?>
<?php require __DIR__ . '/includes/navbar.php'; ?>

<main class="bands-main">

  <!-- LAYOUT TIPO AIRBNB: MAPA 75% + LISTA 25% -->
  <section class="bands-map-shell">
    <div class="container-fluid g-0">
      <div class="row g-0 bands-map-row">

        <!-- MAPA (75%) -->
        <div class="col-lg-9 bands-map-col position-relative">
          <div id="map" class="bands-map bands-map-full"></div>

          <!-- PANEL FLOTANTE DE FILTROS (DESKTOP) -->
          <div class="bands-filters-floating d-none d-lg-flex">
            <div class="bands-filters-inner">
              <span class="bands-filters-label">
                <i class="bi bi-funnel me-1"></i>Filtros
              </span>
              <div class="bands-filters-chips">
                <button type="button" class="bands-chip" data-tier="">
                  Todas
                </button>
                <button type="button" class="bands-chip" data-tier="diamante">
                  Diamante
                </button>
                <button type="button" class="bands-chip" data-tier="oro">
                  Oro
                </button>
                <button type="button" class="bands-chip" data-tier="plata">
                  Plata
                </button>
              </div>
              <button type="button" class="bands-pill-ghost" id="nearMeBtn">
                <i class="bi bi-crosshair2 me-1"></i>Cerca mío
              </button>
            </div>
          </div>

          <!-- PANEL FLOTANTE MOBILE (PARTE INFERIOR DEL MAPA) -->
          <div class="bands-filters-mobile d-flex d-lg-none">
            <div class="bands-filters-mobile-inner">
              <div class="bands-filters-chips">
                <button type="button" class="bands-chip-mobile" data-tier="">
                  Todas
                </button>
                <button type="button" class="bands-chip-mobile" data-tier="diamante">
                  Diamante
                </button>
                <button type="button" class="bands-chip-mobile" data-tier="oro">
                  Oro
                </button>
                <button type="button" class="bands-chip-mobile" data-tier="plata">
                  Plata
                </button>
              </div>
              <button type="button" class="bands-pill-ghost" id="nearMeBtnMobile">
                <i class="bi bi-crosshair2 me-1"></i>Cerca mío
              </button>
            </div>
          </div>
        </div>

        <!-- LISTA DERECHA (25%) - SOLO DESKTOP -->
        <aside class="col-lg-3 bands-list-col d-none d-lg-flex flex-column">
          <header class="bands-list-header d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <div>
                <h2 class="h6 mb-0">Inversiones disponibles</h2>
                <p class="small text-muted mb-0">
                  Explorá las oportunidades en tiempo real.
                </p>
              </div>
            </div>
            <div class="bands-list-subheader small text-muted">
              <span><i class="bi bi-graph-up-arrow me-1 text-bands"></i>Mercado dinámico</span>
              <span>Vista estilo panel de oportunidades</span>
            </div>
          </header>

          <div id="investmentsList" class="bands-investments-list bands-investments-list-aside p-2">
            <!-- Se completa con JS -->
          </div>
        </aside>

      </div>
    </div>
  </section>

  <!-- LISTA MOBILE (DEBAJO DEL MAPA) -->
  <section class="bands-mobile-list-section d-lg-none">
    <div class="container-fluid">
      <div class="d-flex justify-content-between align-items-center mb-2 px-2">
        <div>
          <h2 class="h6 mb-0">Inversiones disponibles</h2>
          <p class="small text-muted mb-0">Tocá una inversión para centrarla en el mapa.</p>
        </div>
      </div>
      <div id="investmentsListMobile" class="bands-investments-list bands-investments-list-mobile px-2 pb-4">
        <!-- Se completa con JS -->
      </div>
    </div>
  </section>

</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
