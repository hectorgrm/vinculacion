<?php
/**
 * Prepara datos para el header del portal en cualquier vista.
 * Intenta usar $portalViewModel (dashboard), luego $empresa/$empresaNombre
 * y finalmente el nombre guardado en la sesiA3n.
 */
$headerEmpresaNombre = null;
$headerLogoUrl = null;

if (isset($portalViewModel) && is_array($portalViewModel)) {
    $headerEmpresaNombre = $portalViewModel['empresaNombre'] ?? null;
    $headerLogoUrl = $portalViewModel['empresaLogoUrl'] ?? null;
}

// Fallbacks para vistas como machote_view.php que no crean $portalViewModel.
if ($headerEmpresaNombre === null && isset($empresaNombre)) {
    $headerEmpresaNombre = (string) $empresaNombre;
}

if ($headerEmpresaNombre === null && isset($empresa['nombre'])) {
    $headerEmpresaNombre = (string) $empresa['nombre'];
}

if ($headerEmpresaNombre === null && isset($portalSession['empresa_nombre'])) {
    $headerEmpresaNombre = (string) $portalSession['empresa_nombre'];
}

$headerEmpresaNombre = trim((string) ($headerEmpresaNombre ?? ''));
if ($headerEmpresaNombre === '') {
    $headerEmpresaNombre = 'Empresa';
}

// Construimos la URL del logo con el mismo criterio usado en el dashboard.
if ($headerLogoUrl === null && isset($empresa['logo_path'])) {
    $logoPath = trim((string) $empresa['logo_path']);
    if ($logoPath !== '') {
        $logoPath = str_replace('\\', '/', $logoPath);
        $logoPath = ltrim($logoPath, '/');
        if ($logoPath !== '' && strpos($logoPath, '..') === false) {
            $headerLogoUrl = preg_match('/^https?:\\/\\//i', $logoPath) === 1
                ? $logoPath
                : '../../recidencia/' . $logoPath;
        }
    }
}

if ($headerLogoUrl === null && isset($portalSession['empresa_logo_path'])) {
    $logoPath = trim((string) $portalSession['empresa_logo_path']);
    if ($logoPath !== '') {
        $logoPath = str_replace('\\', '/', $logoPath);
        $logoPath = ltrim($logoPath, '/');
        if ($logoPath !== '' && strpos($logoPath, '..') === false) {
            $headerLogoUrl = preg_match('/^https?:\\/\\//i', $logoPath) === 1
                ? $logoPath
                : '../../recidencia/' . $logoPath;
        }
    }
}

$headerLogoUrl = $headerLogoUrl ?: 'https://upload.wikimedia.org/wikipedia/commons/a/ac/Default_pfp.jpg';
?>

  <link rel="stylesheet" href="../assets/css/layout/header.css">

<header class="portal-header">
  <div class="brand">
    <!-- dY?� Logotipo dinA�mico -->
    <img src="<?= htmlspecialchars($headerLogoUrl) ?>" alt="Logo de la empresa" />

    <div class="brand-info">
      <strong><?= htmlspecialchars($headerEmpresaNombre) ?></strong>
      <small>Residencias Profesionales</small>
    </div>
  </div>

  <div class="userbox">
    <span class="company"><?= htmlspecialchars($headerEmpresaNombre) ?></span>
    <a href="../view/index.php" class="btn">Inicio</a>
    <a href="../../common/logout.php" class="btn">Salir</a>
  </div>
</header>
