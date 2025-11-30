<?php
declare(strict_types=1);

if (!isset($estudiante)) {
    require __DIR__ . '/../handler/estudiante_view_handler.php';
    return;
}

if (!function_exists('estudianteNombreCompleto')) {
    require_once __DIR__ . '/../helpers/estudiante_helper.php';
}

$empresaNombre = isset($empresaNombre) && $empresaNombre !== ''
    ? (string) $empresaNombre
    : 'Empresa';
$estudiante = is_array($estudiante) ? $estudiante : estudianteEmptyRecord();
$nombreCompleto = isset($nombreCompleto) && $nombreCompleto !== ''
    ? (string) $nombreCompleto
    : estudianteNombreCompleto($estudiante);
$estatusBadgeClass = isset($estatusBadgeClass) ? (string) $estatusBadgeClass : estudianteBadgeClass($estudiante['estatus'] ?? null);
$estatusBadgeLabel = isset($estatusBadgeLabel) ? (string) $estatusBadgeLabel : estudianteBadgeLabel($estudiante['estatus'] ?? null);
$viewErrorMessage = isset($viewErrorMessage) && $viewErrorMessage !== ''
    ? (string) $viewErrorMessage
    : null;
$avatarLetter = 'E';

if ($nombreCompleto !== '') {
    $avatarLetter = function_exists('mb_substr')
        ? mb_substr($nombreCompleto, 0, 1, 'UTF-8')
        : substr($nombreCompleto, 0, 1);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal Empresa · Estudiante</title>
  <link rel="stylesheet" href="../assets/css/modules/estudiante.css">
</head>
<body>

<header class="portal-header">
  <div class="brand">
    <div class="logo"></div>
    <div>
      <strong>Portal de Empresa</strong><br>
      <small>Residencias Profesionales</small>
    </div>
  </div>
  <div class="userbox">
    <span class="company"><?= htmlspecialchars($empresaNombre) ?></span>
    <a href="portal_list.php" class="btn">Inicio</a>
    <a href="../../common/logout.php" class="btn">Salir</a>
  </div>
</header>

<main class="container">

  <section class="titlebar">
    <div>
      <h1>Detalle del estudiante</h1>
      <p>Consulta los datos básicos del estudiante vinculado.</p>
    </div>
    <div class="actions">
      <a class="btn" href="estudiantes_list.php">⬅️ Volver al listado</a>
    </div>
  </section>

  <section class="card">
    <header>Ficha</header>
    <div class="content">
      <?php if ($viewErrorMessage !== null): ?>
        <div class="alert error"><?= htmlspecialchars($viewErrorMessage) ?></div>
      <?php endif; ?>

      <div class="profile">
        <div class="avatar" aria-hidden="true">
          <?= htmlspecialchars($avatarLetter) ?>
        </div>
        <div class="meta">
          <h2 style="margin:0"> <?= htmlspecialchars($nombreCompleto !== '' ? $nombreCompleto : 'Estudiante') ?> </h2>
          <small><?= htmlspecialchars($estudiante['carrera'] ?? '') ?></small>
        </div>
        <div>
          <span class="badge <?= htmlspecialchars($estatusBadgeClass) ?>"><?= htmlspecialchars($estatusBadgeLabel) ?></span>
        </div>
      </div>

      <div class="detail-grid">
        <div class="row"><strong>Matrícula</strong><span><?= htmlspecialchars($estudiante['matricula'] ?? '') ?></span></div>
        <div class="row"><strong>Estatus</strong><span class="badge <?= htmlspecialchars($estatusBadgeClass) ?>" style="margin:0;"><?= htmlspecialchars($estatusBadgeLabel) ?></span></div>
        <div class="row"><strong>Correo institucional</strong><span><?= htmlspecialchars($estudiante['correo_institucional'] ?? '—') ?></span></div>
        <div class="row"><strong>Teléfono</strong><span><?= htmlspecialchars($estudiante['telefono'] !== '' ? $estudiante['telefono'] : '—') ?></span></div>
        <div class="row"><strong>Convenio asociado</strong><span><?= $estudiante['convenio_id'] !== null ? htmlspecialchars((string) $estudiante['convenio_id']) : '—' ?></span></div>
        <div class="row"><strong>Registrado en</strong><span><?= htmlspecialchars($estudiante['creado_en'] ?? '') ?></span></div>
      </div>
    </div>
  </section>

</main>

</body>
</html>
