<?php
declare(strict_types=1);

/** @var array{
 *     documentId: ?int,
 *     document: ?array<string, mixed>,
 *     empresaId: ?int,
 *     tipoGlobalId: ?int,
 *     tipoPersonalizadoId: ?int,
 *     fileMeta: array{
 *         exists: bool,
 *         absolutePath: ?string,
 *         publicUrl: ?string,
 *         filename: ?string,
 *         sizeBytes: ?int,
 *         sizeLabel: ?string,
 *         extension: ?string,
 *         canPreview: bool
 *     },
 *     controllerError: ?string,
 *     notFoundMessage: ?string,
 *     errorMessage: ?string
 * } $handlerResult
 */
$handlerResult = require __DIR__ . '/../../handler/documento/documento_delete_handler.php';

$documentId = $handlerResult['documentId'];
$document = $handlerResult['document'];
$fileMeta = $handlerResult['fileMeta'];
$controllerError = $handlerResult['controllerError'];
$notFoundMessage = $handlerResult['notFoundMessage'];
$errorMessage = $handlerResult['errorMessage'];
$empresaId = $handlerResult['empresaId'];
$tipoGlobalId = $handlerResult['tipoGlobalId'];
$tipoPersonalizadoId = $handlerResult['tipoPersonalizadoId'];

if ($documentId === null && is_array($document) && isset($document['id'])) {
    $documentId = (int) $document['id'];
}

$queryParams = [];
if ($empresaId !== null) {
    $queryParams['empresa'] = (string) $empresaId;
}

$tipoOrigen = is_array($document) && isset($document['tipo_origen']) ? (string) $document['tipo_origen'] : 'global';
if ($tipoOrigen === 'personalizado' && $tipoPersonalizadoId !== null) {
    $queryParams['personalizado'] = (string) $tipoPersonalizadoId;
} elseif ($tipoGlobalId !== null) {
    $queryParams['tipo'] = (string) $tipoGlobalId;
}

$viewUrl = $documentId !== null
    ? 'documento_view.php?id=' . urlencode((string) $documentId)
    : 'documento_list.php';
if ($queryParams !== []) {
    $viewUrl .= (strpos($viewUrl, '?') === false ? '?' : '&') . http_build_query($queryParams);
}

$listUrl = 'documento_list.php';
if ($queryParams !== []) {
    $listUrl .= '?' . http_build_query($queryParams);
}

$empresaUrl = $empresaId !== null ? '../empresa/empresa_view.php?id=' . urlencode((string) $empresaId) : null;

$tipoLabel = 'Tipo sin nombre';
$estatusBadgeClass = 'badge secondary';
$estatusBadgeLabel = 'Sin estatus';
$creadoEnLabel = 'N/D';
$fileSizeLabel = $fileMeta['sizeLabel'] ?? null;
$archivoLabel = $fileMeta['filename'] ?? 'Archivo';
$empresaLabel = 'Empresa sin nombre';
$observacion = '';
$tipoOrigen = 'global';
$tipoObligatorio = false;
if (is_array($document)) {
    if (isset($document['tipo_origen']) && (string) $document['tipo_origen'] !== '') {
        $tipoOrigen = (string) $document['tipo_origen'];
    }
    $tipoObligatorio = !empty($document['tipo_obligatorio']);
}

if (is_array($document)) {
    if (isset($document['tipo_label']) && $document['tipo_label'] !== '') {
        $tipoLabel = (string) $document['tipo_label'];
    } elseif (isset($document['tipo_nombre']) && $document['tipo_nombre'] !== '') {
        $tipoLabel = (string) $document['tipo_nombre'];
    }

    if (isset($document['estatus_badge_class']) && $document['estatus_badge_class'] !== '') {
        $estatusBadgeClass = (string) $document['estatus_badge_class'];
    }

    if (isset($document['estatus_badge_label']) && $document['estatus_badge_label'] !== '') {
        $estatusBadgeLabel = (string) $document['estatus_badge_label'];
    }

    if (isset($document['creado_en_label']) && $document['creado_en_label'] !== '') {
        $creadoEnLabel = (string) $document['creado_en_label'];
    }

    if (isset($document['archivo_nombre']) && $document['archivo_nombre'] !== '') {
        $archivoLabel = (string) $document['archivo_nombre'];
    }

    if (isset($document['empresa_label']) && $document['empresa_label'] !== '') {
        $empresaLabel = (string) $document['empresa_label'];
    } elseif (isset($document['empresa_nombre']) && $document['empresa_nombre'] !== '') {
        $empresaLabel = (string) $document['empresa_nombre'];
    }

    if (isset($document['observacion'])) {
        $observacion = trim((string) $document['observacion']);
    }
}
$empresaEstatus = is_array($document) ? (string) ($document['empresa_estatus'] ?? '') : '';
$empresaIsCompletada = strcasecmp(trim($empresaEstatus), 'Completada') === 0;
$formDisabled = $empresaIsCompletada || $controllerError !== null || $notFoundMessage !== null;
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Eliminar Documento | Residencias Profesionales</title>

  <link rel="stylesheet" href="../../assets/css/modules/documentos/documentodelete.css" />
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>Eliminar Documento<?php echo $documentId !== null ? ' #' . htmlspecialchars((string) $documentId, ENT_QUOTES, 'UTF-8') : ''; ?></h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>&gt;</span>
            <a href="documento_list.php">Documentos</a>
            <span>&gt;</span>
            <span>Eliminar</span>
          </nav>
        </div>
        <div class="top-actions">
          <?php if ($documentId !== null): ?>
            <a href="<?php echo htmlspecialchars($viewUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn secondary">Ver</a>
          <?php endif; ?>
          <a href="<?php echo htmlspecialchars($listUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn secondary">Volver</a>
        </div>
      </header>

      <?php if ($controllerError !== null): ?>
        <section class="card">
          <div class="content">
            <div class="alert error">
              <?php echo htmlspecialchars($controllerError, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          </div>
        </section>
      <?php endif; ?>

      <?php if ($errorMessage !== null): ?>
        <section class="card">
          <div class="content">
            <div class="alert warning">
              <?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          </div>
        </section>
      <?php endif; ?>

      <?php if ($notFoundMessage !== null): ?>
        <section class="card">
          <div class="content">
            <div class="alert warning">
              <?php echo htmlspecialchars($notFoundMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          </div>
        </section>
      <?php endif; ?>

      <?php if ($empresaIsCompletada): ?>
        <section class="card">
          <div class="content">
            <div class="alert warning">
              La empresa est&aacute; en estatus <strong>Completada</strong>. La eliminaci&oacute;n de documentos est&aacute; bloqueada.
            </div>
          </div>
        </section>
      <?php endif; ?>

      <section class="danger-zone">
        <header>Confirmación requerida</header>
        <div class="content">
          <?php if (is_array($document)): ?>
            <p>
              Estás a punto de <strong>eliminar definitivamente</strong> el documento
              <strong>#<?php echo htmlspecialchars((string) $documentId, ENT_QUOTES, 'UTF-8'); ?></strong>
              (<em><?php echo htmlspecialchars((string) $tipoLabel, ENT_QUOTES, 'UTF-8'); ?></em>)
              de la empresa
              <?php if ($empresaUrl !== null): ?>
                <a class="btn secondary" href="<?php echo htmlspecialchars($empresaUrl, ENT_QUOTES, 'UTF-8'); ?>">
                  <?php echo htmlspecialchars((string) $empresaLabel, ENT_QUOTES, 'UTF-8'); ?>
                </a>
              <?php else: ?>
                <span class="badge secondary"><?php echo htmlspecialchars((string) $empresaLabel, ENT_QUOTES, 'UTF-8'); ?></span>
              <?php endif; ?>
              <span class="badge secondary" style="margin-left:6px;">
                <?php echo $tipoOrigen === 'personalizado' ? 'Documento personalizado' : 'Documento global'; ?>
              </span>.
              Esta acción <strong>no se puede deshacer</strong>.
            </p>

            <div class="grid-2" style="margin-top:12px;">
              <div class="card mini">
                <h3>Resumen</h3>
                <p class="text-muted">
                  Tipo: <?php echo htmlspecialchars((string) $tipoLabel, ENT_QUOTES, 'UTF-8'); ?>
                  <?php if ($tipoObligatorio): ?>
                    <span class="badge ok" style="margin-left:6px;">Obligatorio</span>
                  <?php endif; ?>
                </p>
                <p class="text-muted">
                  Origen: <?php echo $tipoOrigen === 'personalizado' ? 'Documento personalizado de la empresa' : 'Documento global'; ?>
                </p>
                <p class="text-muted">
                  Estatus:
                  <span class="<?php echo htmlspecialchars((string) $estatusBadgeClass, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars((string) $estatusBadgeLabel, ENT_QUOTES, 'UTF-8'); ?>
                  </span>
                </p>
                <p class="text-muted">Fecha de carga: <?php echo htmlspecialchars((string) $creadoEnLabel, ENT_QUOTES, 'UTF-8'); ?></p>
                <p class="text-muted">
                  Observación:
                  <?php echo $observacion !== '' ? nl2br(htmlspecialchars($observacion, ENT_QUOTES, 'UTF-8')) : 'Sin observaciones.'; ?>
                </p>
              </div>
              <div class="card mini">
                <h3>Archivo</h3>
                <?php if ($fileMeta['publicUrl'] !== null): ?>
                  <p>
                    <a class="btn secondary" href="<?php echo htmlspecialchars((string) $fileMeta['publicUrl'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">
                      Abrir archivo
                    </a>
                  </p>
                <?php else: ?>
                  <p class="text-muted">No se encontró el archivo asociado.</p>
                <?php endif; ?>
                <p class="text-muted">
                  Nombre: <?php echo htmlspecialchars((string) $archivoLabel, ENT_QUOTES, 'UTF-8'); ?><br>
                  Tamaño: <?php echo htmlspecialchars($fileSizeLabel ?? 'N/D', ENT_QUOTES, 'UTF-8'); ?>
                </p>
              </div>
            </div>
          <?php else: ?>
            <p class="text-muted" style="margin-bottom:0;">
              No se cuenta con información del documento solicitado.
            </p>
          <?php endif; ?>

          <div class="checklist">
            <p><strong>Antes de continuar, verifica lo siguiente:</strong></p>
            <ul class="danger-list">
              <li>Que <strong>no existan revisiones pendientes</strong> asociadas.</li>
              <li>Que <strong>tengas un respaldo</strong> en caso de ser necesario.</li>
            </ul>

            <div class="links-inline">
              <?php if ($empresaUrl !== null): ?>
                <a class="btn secondary" href="<?php echo htmlspecialchars($empresaUrl, ENT_QUOTES, 'UTF-8'); ?>">Ver empresa</a>
              <?php endif; ?>
              <a class="btn secondary" href="<?php echo htmlspecialchars($listUrl, ENT_QUOTES, 'UTF-8'); ?>">Volver a documentos</a>
            </div>
          </div>

          <?php if (is_array($document) && $documentId !== null && !$formDisabled): ?>
            <form action="../../handler/documento/documento_delete_action.php" method="post" style="margin-top:18px;">
              <input type="hidden" name="id" value="<?php echo htmlspecialchars((string) $documentId, ENT_QUOTES, 'UTF-8'); ?>">
              <?php if ($empresaId !== null): ?>
                <input type="hidden" name="empresa_id" value="<?php echo htmlspecialchars((string) $empresaId, ENT_QUOTES, 'UTF-8'); ?>">
              <?php endif; ?>

              <label style="display:flex; gap:8px; align-items:flex-start; margin:12px 0;">
                <input type="checkbox" name="confirm" required>
                <span>He leído las advertencias y deseo <strong>eliminar permanentemente</strong> este documento.</span>
              </label>

              <div class="field" style="margin-top:10px;">
                <label for="motivo">Motivo (opcional)</label>
                <textarea id="motivo" name="motivo" rows="3" maxlength="500" placeholder="Breve explicación para la bitácora interna..."></textarea>
                <small class="text-muted">Este texto no se almacena en la base de datos, se registra en el log de eventos.</small>
              </div>

              <div class="actions" style="justify-content:flex-end;">
                <a href="<?php echo htmlspecialchars($viewUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn secondary">Cancelar</a>
                <button type="submit" class="btn danger">Eliminar documento</button>
              </div>
            </form>

            <p class="text-muted" style="margin-top:10px">
              Sugerencia: si solo quieres ocultarlo del flujo, considera cambiar su estatus a <em>Rechazado</em> en lugar de eliminarlo.
            </p>
          <?php elseif ($empresaIsCompletada): ?>
            <p class="text-muted" style="margin-top:18px;">La eliminaci&oacute;n est&aacute; deshabilitada porque la empresa est&aacute; en estatus Completada.</p>
          <?php endif; ?>
        </div>
      </section>
    </main>
  </div>
</body>

</html>
