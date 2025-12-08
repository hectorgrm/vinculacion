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
 *     formData: array{estatus: string, observacion: string},
 *     statusOptions: array<string, string>,
 *     errors: array<int, string>,
 *     successMessage: ?string,
 *     controllerError: ?string,
 *     notFoundMessage: ?string
 * } $handlerResult
 */
$handlerResult = require __DIR__ . '/../../handler/documento/documento_review_handler.php';

$documentId = $handlerResult['documentId'];
$document = $handlerResult['document'];
$fileMeta = $handlerResult['fileMeta'];
$formData = $handlerResult['formData'];
$statusOptions = $handlerResult['statusOptions'];
$errors = $handlerResult['errors'];
$successMessage = $handlerResult['successMessage'];
$controllerError = $handlerResult['controllerError'];
$notFoundMessage = $handlerResult['notFoundMessage'];
$documentoArchivado = $document !== null && isset($document['estatus'])
  ? (strcasecmp(trim((string)$document['estatus']), 'Archivado') === 0)
  : false;

$empresaId = $document !== null && isset($document['empresa_id']) ? (int) $document['empresa_id'] : null;
$tipoGlobalId = $document !== null && isset($document['tipo_global_id']) ? (int) $document['tipo_global_id'] : null;
if ($tipoGlobalId !== null && $tipoGlobalId <= 0) {
  $tipoGlobalId = null;
}
$tipoPersonalizadoId = $document !== null && isset($document['tipo_personalizado_id']) ? (int) $document['tipo_personalizado_id'] : null;
if ($tipoPersonalizadoId !== null && $tipoPersonalizadoId <= 0) {
  $tipoPersonalizadoId = null;
}
$tipoOrigen = $document['tipo_origen'] ?? 'global';
$fileExtension = isset($fileMeta['extension']) ? strtolower((string) $fileMeta['extension']) : '';
$canRenderImagePreview = in_array($fileExtension, ['png', 'jpg', 'jpeg'], true);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Revisión de Documento | Residencias Profesionales</title>
  <link rel="stylesheet" href="../../assets/css/modules/documentos/documentoreview.css" />
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>Revisión de Documento<?php echo $documentId !== null ? ' #' . htmlspecialchars((string) $documentId, ENT_QUOTES, 'UTF-8') : ''; ?></h2>
          <p>Valida y aprueba o rechaza el documento cargado por la empresa.</p>
        </div>
        <a href="documento_list.php" class="btn secondary">Volver al listado</a>
      </header>

      <?php if ($controllerError !== null): ?>
        <section class="card">
          <div class="content">
            <div class="alert error" role="alert">
              <?php echo htmlspecialchars($controllerError, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          </div>
        </section>
      <?php endif; ?>

      <?php if ($notFoundMessage !== null): ?>
        <section class="card">
          <div class="content">
            <div class="alert warning" role="alert">
              <?php echo htmlspecialchars($notFoundMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          </div>
        </section>
      <?php endif; ?>

      <?php if ($document !== null): ?>
        <?php if ($documentoArchivado): ?>
          <section class="card">
            <div class="content">
              <div class="alert warning" role="alert">
                Este documento est&aacute; en estatus <strong>Archivado</strong>; la revisi&oacute;n est&aacute; en solo lectura.
              </div>
            </div>
          </section>
        <?php endif; ?>
        <section class="card">
          <header>Resumen</header>
          <div class="content">
            <div class="info-grid">
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
                <label>Tipo de documento</label>
                <div>
                  <?php echo htmlspecialchars((string) $document['tipo_label'], ENT_QUOTES, 'UTF-8'); ?>
                  <?php if (!empty($document['tipo_obligatorio'])): ?>
                    <span class="badge ok">Obligatorio</span>
                  <?php endif; ?>
                </div>
              </div>
              <div class="field">
                <label>Origen del documento</label>
                <div><?php echo $tipoOrigen === 'personalizado' ? 'Documento personalizado de la empresa' : 'Documento global'; ?></div>
              </div>
              <div class="field">
                <label>Fecha de carga</label>
                <div><?php echo htmlspecialchars((string) $document['creado_en_label'], ENT_QUOTES, 'UTF-8'); ?></div>
              </div>
              <div class="field">
                <label>Estatus actual</label>
                <span class="<?php echo htmlspecialchars((string) ($document['estatus_badge_class'] ?? 'badge'), ENT_QUOTES, 'UTF-8'); ?>">
                  <?php echo htmlspecialchars((string) ($document['estatus_badge_label'] ?? 'Sin estatus'), ENT_QUOTES, 'UTF-8'); ?>
                </span>
              </div>
            </div>
          </div>
        </section>

        <section class="card">
          <header>Vista previa</header>
          <div class="content preview">
            <?php if ($fileMeta['publicUrl'] !== null && $fileMeta['canPreview']): ?>
              <?php if ($canRenderImagePreview): ?>
                <img
                  src="<?php echo htmlspecialchars((string) $fileMeta['publicUrl'], ENT_QUOTES, 'UTF-8'); ?>"
                  alt="Vista previa del documento"
                  class="preview__image" />
              <?php else: ?>
                <iframe src="<?php echo htmlspecialchars((string) $fileMeta['publicUrl'], ENT_QUOTES, 'UTF-8'); ?>" title="Vista previa del documento"></iframe>
              <?php endif; ?>
            <?php elseif ($fileMeta['publicUrl'] !== null): ?>
              <div class="alert info" role="alert">
                La vista previa está disponible para archivos PDF e imágenes (PNG/JPG). Usa el enlace para descargar el archivo.
              </div>
              <a class="btn" href="<?php echo htmlspecialchars((string) $fileMeta['publicUrl'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">
                Descargar archivo
              </a>
            <?php else: ?>
              <div class="alert warning" role="alert">
                No se encontró el archivo asociado a este documento.
              </div>
            <?php endif; ?>
          </div>
        </section>

        <section class="card">
          <header>Evaluación del documento</header>
          <div class="content">
            <?php if ($successMessage !== null): ?>
              <div class="alert success" role="alert" id="documento-review-success-alert">
                <?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?>
              </div>
            <?php endif; ?>

            <?php if ($errors !== []): ?>
              <div class="alert error" role="alert">
                <ul>
                  <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>

            <form action="documento_review.php?id=<?php echo urlencode((string) $documentId); ?>" method="POST" class="review-form">
              <input type="hidden" name="id" value="<?php echo htmlspecialchars((string) $documentId, ENT_QUOTES, 'UTF-8'); ?>" />
              <div class="field">
                <label>Empresa</label>
                <div><strong><?php echo htmlspecialchars((string) $document['empresa_label'], ENT_QUOTES, 'UTF-8'); ?></strong></div>
              </div>

              <div class="field">
                <label>Tipo de documento</label>
                <div>
                  <?php echo htmlspecialchars((string) $document['tipo_label'], ENT_QUOTES, 'UTF-8'); ?>
                  <?php if (!empty($document['tipo_obligatorio'])): ?>
                    <span class="badge ok">Obligatorio</span>
                  <?php endif; ?>
                </div>
              </div>

              <div class="field">
                <label>Origen del documento</label>
                <div><?php echo $tipoOrigen === 'personalizado' ? 'Documento personalizado de la empresa' : 'Documento global'; ?></div>
              </div>

              <div class="field">
                <label>Estatus actual</label>
                <span class="<?php echo htmlspecialchars((string) ($document['estatus_badge_class'] ?? 'badge'), ENT_QUOTES, 'UTF-8'); ?>">
                  <?php echo htmlspecialchars((string) ($document['estatus_badge_label'] ?? 'Sin estatus'), ENT_QUOTES, 'UTF-8'); ?>
                </span>
              </div>

              <div class="field">
                <label for="estatus">Nuevo estatus</label>
                <select id="estatus" name="estatus" required <?php echo $documentoArchivado ? "disabled" : ""; ?>>
                  <option value="">Selecciona un estatus</option>
                  <?php foreach ($statusOptions as $value => $label): ?>
                    <option value="<?php echo htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); ?>"
                      <?php echo $formData['estatus'] === $value ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="field full">
                <label for="observacion">Observaciones</label>
                <textarea id="observacion" name="observacion" rows="4" placeholder="Escribe observaciones o motivos..." <?php echo $documentoArchivado ? "disabled" : ""; ?>><?php echo htmlspecialchars($formData['observacion'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                <small class="text-muted">Máximo 500 caracteres. Obligatorio si el documento será rechazado.</small>
              </div>

              <div class="actions full">
                <button type="submit" class="btn primary" <?php echo $documentoArchivado ? "disabled" : ""; ?>>Guardar revisi&oacute;n</button>
              </div>
            </form>
          </div>
        </section>
      <?php endif; ?>
    </main>
  </div>
</body>
<?php if ($successMessage !== null): ?>
  <script>
    window.addEventListener('DOMContentLoaded', function () {
      var alertEl = document.getElementById('documento-review-success-alert');
      if (!alertEl) {
        return;
      }

      setTimeout(function () {
        alertEl.remove();
      }, 5000);
    });
  </script>
<?php endif; ?>

</html>

