<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Opcional: si tenés un config.php donde guardás claves:
$maptilerKey = defined('MAPTILER_KEY')
    ? MAPTILER_KEY
    : 'i9tHAYLVopv8PNjMHFYs'; // <-- tu key actual, cámbiala si es necesario
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no"
    >
    <meta name="description" content="BandS Inversiones - Plataforma de oportunidades inmobiliarias geolocalizadas">
    <meta name="author" content="BandS">

    <title>
        BandS Inversiones
        <?php if (!empty($pageTitle)): ?>
            · <?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?>
        <?php endif; ?>
    </title>

    <!-- Favicon (opcional) -->
    <!-- <link rel="icon" type="image/png" href="assets/img/favicon.png"> -->

    <!-- Bootstrap 5 CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"
    >

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    >

    <!-- MapLibre GL CSS -->
    <link
        rel="stylesheet"
        href="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.css"
    >

    <!-- Estilos principales BandS -->
    <link rel="stylesheet" href="assets/css/style.css?v=20251126">

    <!-- Exponer MAPTILER_KEY al frontend -->
    <script>
        window.MAPTILER_KEY = "<?= htmlspecialchars($maptilerKey, ENT_QUOTES, 'UTF-8') ?>";
    </script>

    <!-- MapLibre GL JS (antes de app.js, sin defer para asegurar disponibilidad) -->
    <script src="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.js"></script>

    <!-- Bootstrap Bundle JS (defer para no bloquear pintado) -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"
        defer
    ></script>

    <!-- App JS principal BandS -->
    <script src="assets/js/app.js?v=20251126" defer></script>
</head>
<body class="bg-light">
