<?php
declare(strict_types=1);

require_once __DIR__ . '/../handler/machote_view_handler.php';

$stats = is_array($stats ?? null)
    ? array_merge([
        'total' => 0,
        'pendientes' => 0,
        'resueltos' => 0,
        'progreso' => 0,
        'estado' => 'En revisión',
    ], $stats)
    : [
        'total' => 0,
        'pendientes' => 0,
        'resueltos' => 0,
        'progreso' => 0,
        'estado' => 'En revisión',
    ];

$permisos = is_array($permisos ?? null)
    ? array_merge(['puede_comentar' => false, 'puede_confirmar' => false], $permisos)
    : ['puede_comentar' => false, 'puede_confirmar' => false];

$documento = is_array($documento ?? null)
    ? array_merge([
        'has_html' => false,
        'html' => '',
        'has_pdf' => false,
        'pdf_url' => null,
        'pdf_embed_url' => null,
        'fuente' => null,
    ], $documento)
    : [
        'has_html' => false,
        'html' => '',
        'has_pdf' => false,
        'pdf_url' => null,
        'pdf_embed_url' => null,
        'fuente' => null,
    ];

$machote = is_array($machote ?? null) ? $machote : [];
$comentarios = is_array($comentarios ?? null) ? $comentarios : [];
$empresaNombre = isset($empresaNombre) ? (string) $empresaNombre : 'Empresa';
$estatus = (string) ($stats['estado'] ?? 'En revisión');
$comentAbiertos = (int) ($stats['pendientes'] ?? 0);
$comentResueltos = (int) ($stats['resueltos'] ?? 0);
$avancePct = max(0, min(100, (int) ($stats['progreso'] ?? 0)));
$confirmado = (bool) ($machote['confirmado'] ?? false);
$versionMachote = (string) ($machote['version_local'] ?? 'v1.0');
$machoteId = (int) ($machoteActualId ?? ($machote['id'] ?? 0));

$flashMessages = [];

if (!empty($_GET['comentario_status'])) {
    $statusCode = (string) $_GET['comentario_status'];
    $map = [
        'added' => 'Comentario registrado correctamente.',
    ];
    if (isset($map[$statusCode])) {
        $flashMessages[] = ['type' => 'success', 'text' => $map[$statusCode]];
    }
}

if (!empty($_GET['comentario_error'])) {
    $errorCode = (string) $_GET['comentario_error'];
    $map = [
        'invalid' => 'Completa todos los campos del comentario.',
        'internal' => 'No se pudo guardar tu comentario. Intenta más tarde.',
        'session' => 'Inicia sesión nuevamente para continuar.',
    ];
    if (isset($map[$errorCode])) {
        $flashMessages[] = ['type' => 'error', 'text' => $map[$errorCode]];
    }
}

if (!empty($_GET['confirm_status'])) {
    $statusCode = (string) $_GET['confirm_status'];
    $map = [
        'confirmed' => '¡Gracias! Tu confirmación fue registrada.',
        'already' => 'Este documento ya había sido confirmado previamente.',
    ];
    if (isset($map[$statusCode])) {
        $flashMessages[] = ['type' => 'success', 'text' => $map[$statusCode]];
    }
}

if (!empty($_GET['confirm_error'])) {
    $errorCode = (string) $_GET['confirm_error'];
    $map = [
        'invalid' => 'No fue posible identificar el documento a confirmar.',
        'session' => 'Tu sesión expiró. Inicia sesión nuevamente.',
        'pending' => 'Aún quedan comentarios pendientes por resolver.',
        'internal' => 'Ocurrió un problema al registrar la confirmación.',
    ];
    if (isset($map[$errorCode])) {
        $flashMessages[] = ['type' => 'error', 'text' => $map[$errorCode]];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal Empresa · Revisión del Acuerdo</title>
  <link rel="stylesheet" href="../assets/css/portal/machoteview.css">
</head>
<body>

<header class="portal-header">
  <div class="brand">
    <h1>Portal de Empresa</h1>
    <small>Revisión del acuerdo (machote)</small>
  </div>
  <div class="userbox">
    <span class="company"><?= htmlspecialchars($empresaNombre) ?></span>
    <a href="../portalacceso/portal_list.php" class="btn small">🏠 Inicio</a>
    <a href="../../common/logout.php" class="btn small danger">Salir</a>
  </div>
</header>

<main class="layout">

  <!-- Columna izquierda -->
  <section class="left">

    <?php if (!empty($viewError)): ?>
      <div class="card">
        <header>Error</header>
        <div class="content">
          <p class="warn">⚠️ <?= htmlspecialchars($viewError) ?></p>
        </div>
      </div>
    <?php endif; ?>

    <?php foreach ($flashMessages as $flash): ?>
      <div class="card flash <?= htmlspecialchars($flash['type']) ?>">
        <div class="content">
          <?= htmlspecialchars($flash['text']) ?>
        </div>
      </div>
    <?php endforeach; ?>

    <!-- Estado general -->
    <div class="card">
      <header>Estado general</header>
      <div class="content kpis">
        <div class="metric"><div class="num"><?= $comentAbiertos ?></div><div class="lbl">Pendientes</div></div>
        <div class="metric"><div class="num"><?= $comentResueltos ?></div><div class="lbl">Atendidos</div></div>
        <div class="metric"><div class="num"><?= $avancePct ?>%</div><div class="lbl">Avance</div></div>
        <div class="metric status">
          <?php $estatusLower = function_exists('mb_strtolower') ? mb_strtolower($estatus, 'UTF-8') : strtolower($estatus); ?>
          <?php if ($estatusLower === 'aprobado'): ?>
            <span class="badge ok">Aprobado</span>
          <?php elseif ($estatusLower === 'con observaciones'): ?>
            <span class="badge warn">Con observaciones</span>
          <?php else: ?>
            <span class="badge warn"><?= htmlspecialchars($estatus) ?></span>
          <?php endif; ?>
        </div>
      </div>
      <div class="progressbox">
        <div class="progress"><div class="bar" style="width:<?= $avancePct ?>%"></div></div>
      </div>
    </div>

    <!-- Documento -->
    <div class="card">
      <header>Documento a revisar · <?= htmlspecialchars($versionMachote) ?></header>
      <div class="content">
        <?php if ($documento['has_pdf'] && $documento['pdf_embed_url'] !== null): ?>
          <div class="pdf-frame">
            <iframe src="<?= htmlspecialchars((string) $documento['pdf_embed_url']) ?>" title="Machote PDF"></iframe>
          </div>
        <?php elseif ($documento['has_html']): ?>
          <div class="html-viewer">
            <?= $documento['html'] ?>
          </div>
        <?php else: ?>
          <p class="empty">No hay un documento disponible para mostrar.</p>
        <?php endif; ?>

        <div class="file-actions">
          <?php if ($documento['has_pdf'] && $documento['pdf_url'] !== null): ?>
            <a class="btn" href="<?= htmlspecialchars((string) $documento['pdf_url']) ?>" target="_blank">📄 Ver PDF</a>
            <a class="btn" download href="<?= htmlspecialchars((string) $documento['pdf_url']) ?>">⬇️ Descargar</a>
          <?php elseif ($documento['has_html']): ?>
            <span class="note">Se muestra el contenido HTML registrado por Vinculación.</span>
          <?php else: ?>
            <span class="note">Pendiente de cargar archivo o contenido.</span>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Confirmación -->
    <div class="card">
      <header>Confirmación de Empresa</header>
      <div class="content approval">
        <form action="../handler/mcahote_confirm_handler.php" method="post">
          <input type="hidden" name="machote_id" value="<?= $machoteId ?>">
          <label class="switch">
            <input type="checkbox" name="confirmacion_empresa" value="1" <?= $confirmado ? 'checked disabled' : '' ?>>
            <span class="slider"></span>
            <span class="label">Estoy de acuerdo con el contenido del documento</span>
          </label>
          <?php if ($confirmado): ?>
            <p class="ok-note">✅ Confirmación registrada.</p>
          <?php else: ?>
            <div class="actions">
              <button type="submit" class="btn primary" <?= $permisos['puede_confirmar'] ? '' : 'disabled' ?>>💾 Guardar confirmación</button>
            </div>
            <?php if (!$permisos['puede_confirmar']): ?>
              <p class="note">Resuelve primero los comentarios pendientes para habilitar la confirmación.</p>
            <?php endif; ?>
          <?php endif; ?>
        </form>
      </div>
    </div>

  </section>

  <!-- Columna derecha -->
  <section class="right">

    <!-- Nuevo comentario -->
    <div class="card">
      <header>Agregar comentario</header>
      <div class="content">
        <?php if ($permisos['puede_comentar']): ?>
        <form action="../handler/machote_comentario_add_handler.php" method="post">
          <input type="hidden" name="machote_id" value="<?= $machoteId ?>">
          <div class="field">
            <label for="asunto">Tema</label>
            <input type="text" id="asunto" name="asunto" required>
          </div>
          <div class="field">
            <label for="comentario">Descripción</label>
            <textarea id="comentario" name="comentario" rows="3" required></textarea>
          </div>
          <div class="actions">
            <button class="btn primary">💬 Publicar comentario</button>
          </div>
        </form>
        <?php else: ?>
          <p class="note">Los comentarios están disponibles solo mientras el convenio está en revisión.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Lista de comentarios -->
    <div class="card">
      <header>Comentarios recientes</header>
      <div class="content threads">
        <?php if (count($comentarios) === 0): ?>
          <p class="empty">Aún no hay comentarios registrados.</p>
        <?php else: ?>
          <?php foreach ($comentarios as $comentario): ?>
            <article class="thread">
              <div class="meta">
                <span class="badge <?= ($comentario['estatus'] ?? '') === 'resuelto' ? 'resuelto' : 'abierto' ?>">
                  <?= htmlspecialchars(ucfirst((string) ($comentario['estatus'] ?? 'pendiente'))) ?>
                </span>
                <span class="author <?= $comentario['autor_rol'] === 'admin' ? 'admin' : 'empresa' ?>">
                  <?= htmlspecialchars($comentario['autor_nombre'] ?? '') ?>
                </span>
                <?php if (!empty($comentario['fecha_label'])): ?>
                  <span class="time"><?= htmlspecialchars((string) $comentario['fecha_label']) ?></span>
                <?php endif; ?>
              </div>
              <?php if (!empty($comentario['clausula'])): ?>
                <h4><?= htmlspecialchars((string) $comentario['clausula']) ?></h4>
              <?php endif; ?>
              <p><?= nl2br(htmlspecialchars((string) ($comentario['comentario'] ?? ''))) ?></p>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

  </section>
</main>

<footer class="portal-foot">
  <small>Portal de Empresa · Área de Vinculación</small>
</footer>
</body>
</html>
