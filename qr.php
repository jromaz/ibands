<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'QR de inversión';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/navbar.php';

$rawData = isset($_GET['data']) ? trim($_GET['data']) : '';
?>
<main class="bands-main">
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6">

        <div class="mb-3">
          <h1 class="h5 mb-1">Compartir inversión mediante QR</h1>
          <p class="small text-muted mb-0">
            Escaneá este código con tu teléfono o visor para abrir la oportunidad directamente.
          </p>
        </div>

        <?php if ($rawData === ''): ?>
          <div class="alert alert-warning">
            No se recibió ninguna información para generar el código QR.
          </div>
        <?php else: ?>
          <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-body text-center">
              <input type="hidden"
                     id="qrDataValue"
                     value="<?= htmlspecialchars($rawData, ENT_QUOTES, 'UTF-8') ?>">

              <div id="qrcode" class="d-inline-block mb-3"></div>

              <p class="small text-muted mb-1">
                Link codificado:
              </p>
              <p class="small text-break">
                <?= htmlspecialchars($rawData, ENT_QUOTES, 'UTF-8') ?>
              </p>
            </div>
          </div>

          <p class="small text-muted">
            Consejo: podés proyectar esta pantalla en una TV o monitor para que otras personas
            escaneen el código con su celular y accedan directamente a la ficha de la inversión.
          </p>
        <?php endif; ?>

      </div>
    </div>
  </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>

<!-- Librería QRCodeJS desde CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"
        integrity="sha512-MnIXx36lGqvFqKht16Dy1pZ8ADK5vVsQt1zAr72Xd1LSeX776BFqe7i/Dr7guP5AnbcWc57Vdc+XUuKvj6ImDw=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var valueInput = document.getElementById('qrDataValue');
    if (!valueInput) return;

    var data = valueInput.value;
    if (!data) return;

    var container = document.getElementById('qrcode');
    if (!container) return;

    new QRCode(container, {
      text: data,
      width: 240,
      height: 240,
      colorDark: "#000000",
      colorLight: "#ffffff",
      correctLevel: QRCode.CorrectLevel.H
    });
  });
</script>
