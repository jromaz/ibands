<?php
// register.php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role_id = (int)($_POST['role_id'] ?? 0);

    if (!$name || !$email || !$password || !$role_id) {
        $error = 'Todos los campos son obligatorios.';
    } else {
        try {
            // Check if email exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            if ($stmt->fetch()) {
                $error = 'El correo ya está registrado.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role_id, is_active) VALUES (:name, :email, :hash, :role_id, 1)");
                $stmt->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':hash' => $hash,
                    ':role_id' => $role_id
                ]);
                
                // Auto login
                load_user_into_session($pdo, (int)$pdo->lastInsertId());
                header('Location: dashboard.php');
                exit;
            }
        } catch (Exception $e) {
            $error = 'Error al registrar: ' . $e->getMessage();
        }
    }
}

// Get Roles for Selector
$roles = [];
try {
    $stmt = $pdo->query("SELECT id, name, description FROM roles WHERE name IN ('inversor', 'agente_inmobiliario', 'visitante')");
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Ignore
}

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/navbar.php';
?>

<main class="bands-main bands-login-main">
  <div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="bands-login-card p-4">
                <h1 class="h4 mb-3">Crear cuenta</h1>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger small"><?= $error ?></div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3">
                        <label class="form-label small">Nombre completo</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Correo electrónico</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Tipo de Perfil</label>
                        <select name="role_id" class="form-select" required>
                            <option value="">Selecciona...</option>
                            <?php foreach ($roles as $r): ?>
                                <option value="<?= $r['id'] ?>">
                                    <?= ucfirst(str_replace('_', ' ', $r['name'])) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                </form>
                
                <div class="mt-3 text-center small">
                    ¿Ya tienes cuenta? <a href="login.php">Iniciar sesión</a>
                </div>
            </div>
        </div>
    </div>
  </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
