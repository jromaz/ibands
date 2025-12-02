<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Entorno Oculus';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/navbar.php';

$investmentId = isset($_GET['investment_id']) ? (int) $_GET['investment_id'] : 0;
$investment = null;
$errorMsg = '';

if ($investmentId > 0) {
    try {
        // Asumiendo que en db.php definís $pdo (PDO)
        $stmt = $pdo->prepare("SELECT * FROM investments WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $investmentId]);
        $investment = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$investment) {
            $errorMsg = 'No se encontró la inversión solicitada.';
        }
    } catch (Throwable $e) {
        $errorMsg = 'Ocurrió un error al buscar la inversión.';
    }
} else {
    $errorMsg = 'No se especificó ninguna inversión.';
}
?>

<main class="bands-main">
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-8">

        <div class="mb-3">
          <h1 class="h5 mb-1">Entorno inmersivo · Oculus / VR</h1>
          <p class="small text-muted mb-0">
            Visualizá esta inversión en un entorno diseñado para gafas Oculus o experiencias 3D.
          </p>
        </div>

        <?php if ($errorMsg): ?>
          <div class="alert alert-warning">
            <?= htmlspecialchars($errorMsg, ENT_QUOTES, 'UTF-8') ?>
          </div>
        <?php else: ?>

          <?php
          $title    = $investment['title'] ?? 'Inversión';
          $loc      = $investment['location_name'] ?? '';
          $desc     = $investment['description'] ?? '';
          $imgUrl   = $investment['image_url'] ?? '';
          $status   = $investment['status'] ?? '';
          $ticket   = $investment['min_ticket'] ?? null;
          $progress = $investment['progress_percent'] ?? null;

          $vrUrl = 'vr/index.html?investment_id=' . urlencode((string)$investmentId);
          ?>

          <div class="card border-0 shadow-sm rounded-4 mb-3">
            <?php if ($imgUrl): ?>
              <div class="ratio ratio-16x9">
                <img src="<?= htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8') ?>"
                     class="w-100 h-100"
                     style="object-fit: cover;"
                     alt="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>">
              </div>
            <?php endif; ?>

            <div class="card-body">
              <h2 class="h5 mb-1"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h2>

              <?php if ($loc): ?>
                <p class="mb-1 small text-muted">
                  <i class="bi bi-geo-alt me-1"></i>
                  <?= htmlspecialchars($loc, ENT_QUOTES, 'UTF-8') ?>
                </p>
              <?php endif; ?>

              <?php if ($desc): ?>
                <p class="mb-2">
                  <?= nl2br(htmlspecialchars($desc, ENT_QUOTES, 'UTF-8')) ?>
                </p>
              <?php endif; ?>

              <div class="d-flex flex-wrap gap-2 small text-muted mb-3">
                <?php if ($ticket !== null): ?>
                  <span>
                    <i class="bi bi-cash-coin me-1"></i>
                    Ticket mínimo: USD <?= number_format((float)$ticket, 0, ',', '.') ?>
                  </span>
                <?php endif; ?>
                <?php if ($progress !== null): ?>
                  <span>
                    <i class="bi bi-bar-chart-line me-1"></i>
                    Avance: <?= (int)$progress ?>%
                  </span>
                <?php endif; ?>
                <?php if ($status): ?>
                  <span>
                    <i class="bi bi-circle-fill me-1"></i>
                    Estado: <?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>
                  </span>
                <?php endif; ?>
              </div>

              <div class="alert alert-info small mb-3">
                <strong>Modo Oculus / VR (beta):</strong><br>
                Esta vista está pensada para integrarse con un entorno WebXR u otra
                experiencia 3D. Podés abrirla desde un navegador dentro de tu visor
                Oculus para comenzar a probar el flujo.
              </div>

              <div class="d-flex flex-wrap gap-2">
                <a href="<?= htmlspecialchars($vrUrl, ENT_QUOTES, 'UTF-8') ?>"
                   class="btn btn-sm btn-primary">
                  <i class="bi bi-headset-vr me-1"></i>
                  Entrar a entorno inmersivo (beta)
                </a>

                <a href="index.php"
                   class="btn btn-sm btn-outline-secondary">
                  <i class="bi bi-arrow-left me-1"></i>
                  Volver al mapa
                </a>
              </div>

            </div>
          </div>

        <?php endif; ?>

      </div>
    </div>
  </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
