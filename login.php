<?php
// login.php

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

// Verificamos que $pdo exista (por si hay algún problema de config)
if (!isset($pdo) || !$pdo instanceof PDO) {
    die('ERROR: La conexión $pdo no está disponible. Revisá includes/db.php');
}

// Si ya está logueado, lo mandamos al dashboard
if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error = null;
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Por favor, completá tu correo y contraseña.';
    } else {
        try {
            $sql = "
                SELECT
                  id,
                  name,
                  email,
                  password_hash,
                  role_id,
                  is_active
                FROM users
                WHERE email = :email
                LIMIT 1
            ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            if (!$user) {
                $error = 'Correo o contraseña incorrectos.';
            } elseif ((int)$user['is_active'] !== 1) {
                $error = 'Tu cuenta está inactiva. Contactá al administrador.';
            } elseif (!password_verify($password, $user['password_hash'])) {
                $error = 'Correo o contraseña incorrectos.';
            } else {
                // Login correcto: cargamos el usuario completo en sesión
                load_user_into_session($pdo, (int)$user['id']);
                header('Location: dashboard.php');
                exit;
            }
        } catch (Throwable $e) {
            // Podés loguear el error en un archivo si lo necesitás
            $error = 'Ocurrió un error al iniciar sesión. Intentalo nuevamente.';
        }
    }
}

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/navbar.php';
?>

<main class="bands-main bands-login-main">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-6 col-lg-4">
        <div class="bands-login-card p-4 p-md-4">
          <div class="mb-3">
            <h1 class="h4 mb-1">Iniciá sesión en BandS</h1>
            <p class="small text-muted mb-0">
              Accedé a tus inversiones, propiedades y panel personalizado.
            </p>
          </div>

          <?php if ($error): ?>
            <div class="alert alert-danger py-2 small">
              <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <form method="post" novalidate>
            <div class="mb-3">
              <label for="email" class="form-label small">Correo electrónico</label>
              <input
                type="email"
                class="form-control form-control-sm"
                id="email"
                name="email"
                required
                value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>"
                placeholder="tu-correo@ejemplo.com">
            </div>

            <div class="mb-3">
              <label for="password" class="form-label small">Contraseña</label>
              <input
                type="password"
                class="form-control form-control-sm"
                id="password"
                name="password"
                required
                placeholder="••••••••">
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="form-check small">
                <input class="form-check-input" type="checkbox" value="" id="remember">
                <label class="form-check-label" for="remember">
                  Mantener sesión
                </label>
              </div>
              <a href="#" class="small text-decoration-none text-muted">
                ¿Olvidaste tu contraseña?
              </a>
            </div>

            <button type="submit" class="btn btn-warning w-100 mb-3">
              Continuar
            </button>

            <div class="text-center small text-muted">
              ¿No tenés cuenta?
              <a href="register.php" class="text-decoration-none">
                Crear una nueva
              </a>
            </div>
          </form>
        </div>

        <p class="small text-center text-muted mt-3 mb-0">
          Experiencia inspirada en el login de Airbnb, adaptada a BandS Inversiones.
        </p>
      </div>
    </div>
  </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
