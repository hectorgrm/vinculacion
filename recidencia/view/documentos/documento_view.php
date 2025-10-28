<?php
declare(strict_types=1);

/** @var array{
 *     documentId: ?int,
 *     document: ?array<string, mixed>,
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
 *     history: array<int, array<string, mixed>>,
 *     controllerError: ?string,
 *     notFoundMessage: ?string
 * } $handlerResult
 */
$handlerResult = require __DIR__ . '/../../handler/documento/documento_view_handler.php';

$documentId = $handlerResult['documentId'];
$document = $handlerResult['document'];
$fileMeta = $handlerResult['fileMeta'];
$history = $handlerResult['history'];
$controllerError = $handlerResult['controllerError'];
$notFoundMessage = $handlerResult['notFoundMessage'];

$empresaId = $document !== null && isset($document['empresa_id']) ? (int) $document['empresa_id'] : null;
$convenioId = $document !== null && isset($document['convenio_id']) ? (int) $document['convenio_id'] : null;
$tipoId = $document !== null && isset($document['tipo_id']) ? (int) $document['tipo_id'] : null;
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detalle del Documento - Residencias Profesionales</title>

  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>Detalle del Documento<?php echo $documentId !== null ? ' #' . htmlspecialchars((string) $documentId, ENT_QUOTES, 'UTF-8') : ''; ?></h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>/</span>
            <a href="documento_list.php">Documentos</a>
            <span>/</span>
            <span>Ver</span>
          </nav>
        </div>
        <div class="top-actions" style="display:flex; gap:10px; flex-wrap:wrap;">
          <?php if ($fileMeta['publicUrl'] !== null): ?>
            <a href="<?php echo htmlspecialchars((string) $fileMeta['publicUrl'], ENT_QUOTES, 'UTF-8'); ?>" class="btn" target="_blank" rel="noopener noreferrer">Descargar</a>
          <?php endif; ?>
          <a href="documento_list.php" class="btn">Volver</a>
        </div>
      </header>

      <?php if ($controllerError !== null): ?>
        <section class="card">
          <div class="content">
            <div class="alert alert-danger">
              <?php echo htmlspecialchars($controllerError, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          </div>
        </section>
      <?php endif; ?>

      <?php if ($notFoundMessage !== null): ?>
        <section class="card">
          <div class="content">
            <div class="alert alert-warning">
              <?php echo htmlspecialchars($notFoundMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          </div>
        </section>
      <?php endif; ?>

      <?php if ($document !== null): ?>
        <section class="card">
          <header>Informaci&oacute;n del Documento</header>
          <div class="content">
            <div class="info-grid">
              <div class="field">
                <label>Documento</label>
                <div>#<?php echo htmlspecialchars((string) $document['id'], ENT_QUOTES, 'UTF-8'); ?></div>
              </div>

              <div class="field">
                <label>Empresa</label>
                <?php if ($empresaId !== null): ?>
                  <div>
                    <a class="btn" href="../empresa/empresa_view.php?id=<?php echo urlencode((string) $empresaId); ?>">
                      <?php echo htmlspecialchars((string) $document['empresa_label'], ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                  </div>
                <?php else: ?>
                  <div><?php echo htmlspecialchars((string) $document['empresa_label'], ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>
              </div>

              <div class="field">
                <label>Convenio</label>
                <?php if ($convenioId !== null && $document['convenio_label'] !== null): ?>
                  <div>
                    <a class="btn" href="../convenio/convenio_view.php?id=<?php echo urlencode((string) $convenioId); ?>">
                      <?php echo htmlspecialchars((string) $document['convenio_label'], ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                  </div>
                <?php else: ?>
                  <div>Sin convenio asociado</div>
                <?php endif; ?>
              </div>

              <div class="field">
                <label>Tipo</label>
                <div><?php echo htmlspecialchars((string) $document['tipo_label'], ENT_QUOTES, 'UTF-8'); ?></div>
              </div>

              <div class="field">
                <label>Estatus</label>
                <div>
                  <span class="<?php echo htmlspecialchars((string) $document['estatus_badge_class'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars((string) $document['estatus_badge_label'], ENT_QUOTES, 'UTF-8'); ?>
                  </span>
                </div>
              </div>

              <div class="field">
                <label>Fecha de carga</label>
                <div><?php echo htmlspecialchars((string) $document['creado_en_label'], ENT_QUOTES, 'UTF-8'); ?></div>
              </div>

              <div class="field">
                <label>Archivo</label>
                <?php if ($fileMeta['publicUrl'] !== null): ?>
                  <div>
                    <a class="btn" href="<?php echo htmlspecialchars((string) $fileMeta['publicUrl'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">
                      <?php echo htmlspecialchars($fileMeta['filename'] ?? 'Abrir archivo', ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                  </div>
                <?php else: ?>
                  <div>No disponible</div>
                <?php endif; ?>
              </div>

              <div class="field">
                <label>Tamano</label>
                <div><?php echo htmlspecialchars($fileMeta['sizeLabel'] ?? 'N/D', ENT_QUOTES, 'UTF-8'); ?></div>
              </div>

              <div class="field col-span-2">
                <label>Observaciones</label>
                <div class="text-muted">
                  <?php
                  $observacion = isset($document['observacion']) ? trim((string) $document['observacion']) : '';
                  echo $observacion !== ''
                    ? nl2br(htmlspecialchars($observacion, ENT_QUOTES, 'UTF-8'))
                    : 'Sin observaciones registradas.';
                  ?>
                </div>
              </div>
            </div>

            <div class="actions" style="justify-content:flex-start;">
              <?php if ($empresaId !== null && $tipoId !== null): ?>
                <?php
                $uploadUrl = 'documento_upload.php?empresa=' . urlencode((string) $empresaId)
                  . '&tipo=' . urlencode((string) $tipoId);
                if ($convenioId !== null) {
                  $uploadUrl .= '&convenio=' . urlencode((string) $convenioId);
                }
                ?>
                <a class="btn" href="<?php echo htmlspecialchars($uploadUrl, ENT_QUOTES, 'UTF-8'); ?>">Subir nueva version</a>
              <?php endif; ?>
              <?php if ($empresaId !== null): ?>
                <a class="btn" href="documento_list.php?empresa=<?php echo urlencode((string) $empresaId); ?>">Ver documentos de esta empresa</a>
              <?php endif; ?>
            </div>
          </div>
        </section>

        <section class="card">
          <header>Vista rapida</header>
          <div class="content preview">
            <?php if ($fileMeta['canPreview'] && $fileMeta['publicUrl'] !== null): ?>
              <iframe src="<?php echo htmlspecialchars((string) $fileMeta['publicUrl'], ENT_QUOTES, 'UTF-8'); ?>" style="width:100%; height:420px; border:0;" title="Vista previa del documento"></iframe>
            <?php elseif ($fileMeta['publicUrl'] !== null): ?>
              <div class="alert alert-info">
                La vista previa solo esta disponible para archivos PDF. Usa el boton descargar para abrir el archivo.
              </div>
            <?php else: ?>
              <div class="alert alert-warning">
                No se encontro el archivo asociado al documento.
              </div>
            <?php endif; ?>
          </div>
        </section>

        <section class="card">
          <header>Historial relacionado</header>
          <div class="content">
            <?php if ($history !== []): ?>
              <ul style="margin:0; padding-left:18px; color:#334155">
                <?php foreach ($history as $entry): ?>
                  <li>
                    <strong><?php echo htmlspecialchars((string) $entry['creado_en_label'], ENT_QUOTES, 'UTF-8'); ?></strong>
                    &mdash;
                    <span class="<?php echo htmlspecialchars((string) $entry['estatus_badge_class'], ENT_QUOTES, 'UTF-8'); ?>">
                      <?php echo htmlspecialchars((string) $entry['estatus_badge_label'], ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                    <?php if (!empty($entry['archivo_nombre'])): ?>
                      <span style="margin-left:6px;">
                        Archivo: <?php echo htmlspecialchars((string) $entry['archivo_nombre'], ENT_QUOTES, 'UTF-8'); ?>
                      </span>
                    <?php endif; ?>
                    <a class="btn small" style="margin-left:8px;" href="documento_view.php?id=<?php echo urlencode((string) $entry['id']); ?>">Ver</a>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <p class="text-muted" style="margin:0;">No hay otros documentos relacionados para este tipo.</p>
            <?php endif; ?>
          </div>
        </section>
        <?php if ($document['estatus'] === 'aprobado'): ?>
          <section class="card">
            <header>&#128260; Reabrir revisi&oacute;n</header>
            <div class="content">
              <p class="text-muted">
                Este documento est&aacute; actualmente <strong>aprobado</strong>.  
                Si detectaste alg&uacute;n error o necesitas volver a evaluarlo, puedes reabrir la revisi&oacute;n.  
                Esto cambiar&aacute; su estatus a <em>pendiente</em> y ser&aacute; visible nuevamente para revisi&oacute;n.
              </p>
              <form action="../../handler/documento/documento_reopen_action.php" method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars((string) $document['id'], ENT_QUOTES, 'UTF-8'); ?>">
                <button type="submit" class="btn warn">&#128260; Reabrir revisi&oacute;n</button>
              </form>
            </div>
          </section>
        <?php endif; ?>

        <div class="actions">
          <a href="documento_delete.php?id=<?php echo urlencode((string) $document['id']); ?>" class="btn danger">Eliminar documento</a>
        </div>
      <?php endif; ?>
    </main>
  </div>
</body>

</html>
