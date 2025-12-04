<?php
declare(strict_types=1);

/** @var array{
 *     empresaId: ?int,
 *     empresa: ?array<string, mixed>,
 *     globalDocuments: array<int, array<string, mixed>>,
 *     customDocuments: array<int, array<string, mixed>>,
 *     documentos: array<int, array<string, mixed>>,
 *     stats: array<string, int>,
 *     controllerError: ?string,
 *     inputError: ?string,
 *     notFoundMessage: ?string,
 *     statusMessage: ?string
 * } $handlerResult
 */
$handlerResult = require __DIR__ . '/../../handler/empresadocumentotipo/empresa_documentotipo_list_handler.php';

$empresaId = $handlerResult['empresaId'];
$empresa = $handlerResult['empresa'];
$globalDocuments = $handlerResult['globalDocuments'];
$customDocuments = $handlerResult['customDocuments'];
$documentos = $handlerResult['documentos'] ?? [];
$stats = $handlerResult['stats'] ?? ['total' => 0, 'subidos' => 0, 'aprobados' => 0, 'porcentaje' => 0];
$controllerError = $handlerResult['controllerError'];
$inputError = $handlerResult['inputError'];
$notFoundMessage = $handlerResult['notFoundMessage'];
$statusMessage = $handlerResult['statusMessage'] ?? null;

$empresaNombre = is_array($empresa) ? (string) ($empresa['nombre_label'] ?? ($empresa['nombre'] ?? 'Sin datos')) : 'Sin datos';
$empresaRfc = is_array($empresa) ? (string) ($empresa['rfc_label'] ?? ($empresa['rfc'] ?? 'N/A')) : 'N/A';
$regimenFiscal = is_array($empresa) ? (string) ($empresa['regimen_label'] ?? ($empresa['regimen_fiscal'] ?? '')) : '';
$empresaEstatus = is_array($empresa) ? (string) ($empresa['estatus'] ?? '') : '';
$empresaIsCompletada = strcasecmp(trim($empresaEstatus), 'Completada') === 0;
$empresaIdQuery = $empresaId !== null ? (string) $empresaId : '';

$statsTotal = (int) ($stats['total'] ?? 0);
$statsSubidos = (int) ($stats['subidos'] ?? 0);
$statsAprobados = (int) ($stats['aprobados'] ?? 0);
$statsPorcentaje = (int) ($stats['porcentaje'] ?? 0);
$statsPendientes = max(0, $statsTotal - $statsSubidos);

$errorMessage = $controllerError ?? $inputError ?? $notFoundMessage ?? null;

$addDocumentUrl = 'empresa_documentotipo_add.php' . ($empresaIdQuery !== '' ? '?id_empresa=' . urlencode($empresaIdQuery) : '');
$backUrl = '../empresa/empresa_view.php' . ($empresaIdQuery !== '' ? '?id=' . urlencode($empresaIdQuery) : '');
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Documentacion de Empresa - Residencias Profesionales</title>

  <link rel="stylesheet" href="../../assets/css/modules/documentotipo/empresadocumentotipolist.css" />
  <link rel="stylesheet" href="../../assets/css/modules/empresa.css" />
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div class="page-titles">
          <p class="eyebrow">Documentos</p>
          <h2>Documentación de Empresa</h2>
          <p class="lead">Control y seguimiento de los requisitos documentales registrados para la empresa seleccionada.</p>
        </div>
        <div class="actions">
          <?php if ($empresaIdQuery !== ''): ?>
            <a href="<?php echo htmlspecialchars($backUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn secondary">Volver</a>
          <?php endif; ?>
          <?php if (!$empresaIsCompletada): ?>
            <a href="<?php echo htmlspecialchars($addDocumentUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn primary">Agregar documento individual</a>
          <?php endif; ?>
        </div>
      </header>

      <section class="card">
        <header>
          <div>
            <p class="eyebrow">Resumen</p>
            <h3 class="section-title">Estado general de documentación</h3>
            <p class="muted">Consulta el estado general de los documentos requeridos.</p>
          </div>
          <?php if ($empresaIdQuery !== ''): ?>
            <span class="pill">Empresa #<?php echo htmlspecialchars($empresaIdQuery, ENT_QUOTES, 'UTF-8'); ?></span>
          <?php endif; ?>
        </header>

        <div class="content">
          <?php if ($errorMessage !== null || ($statusMessage !== null && $errorMessage === null)): ?>
            <div class="message-stack">
              <?php if ($errorMessage !== null): ?>
                <div class="alert error" role="alert">
                  <?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
                </div>
              <?php endif; ?>
              <?php if ($statusMessage !== null && $errorMessage === null): ?>
                <div class="alert success" role="status">
                  <?php echo htmlspecialchars($statusMessage, ENT_QUOTES, 'UTF-8'); ?>
                </div>
              <?php endif; ?>
            </div>
          <?php endif; ?>

          <div class="summary-grid">
            <div class="summary-card">
              <span class="eyebrow">Empresa</span>
              <div class="value"><?php echo htmlspecialchars($empresaNombre, ENT_QUOTES, 'UTF-8'); ?></div>
              <p class="meta">RFC: <?php echo htmlspecialchars($empresaRfc, ENT_QUOTES, 'UTF-8'); ?></p>
              <?php if ($regimenFiscal !== ''): ?>
                <p class="meta">Régimen: <?php echo htmlspecialchars($regimenFiscal, ENT_QUOTES, 'UTF-8'); ?></p>
              <?php endif; ?>
            </div>

            <div class="summary-card">
              <span class="eyebrow">Avance</span>
              <div class="value"><?php echo htmlspecialchars((string) $statsSubidos, ENT_QUOTES, 'UTF-8'); ?> / <?php echo htmlspecialchars((string) $statsTotal, ENT_QUOTES, 'UTF-8'); ?> subidos</div>
              <div class="progress-bar">
                <div class="progress-fill" style="width: <?php echo max(0, min(100, $statsPorcentaje)); ?>%;"></div>
              </div>
              <div class="progress-meta">
                <span><?php echo htmlspecialchars((string) $statsPorcentaje, ENT_QUOTES, 'UTF-8'); ?>% completado</span>
                <span><?php echo htmlspecialchars((string) $statsAprobados, ENT_QUOTES, 'UTF-8'); ?> aprobados</span>
              </div>
            </div>

            <div class="summary-card">
              <span class="eyebrow">Seguimiento</span>
              <div class="pill-row">
                <span class="pill">Total: <?php echo htmlspecialchars((string) $statsTotal, ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="pill pill--success">Aprobados: <?php echo htmlspecialchars((string) $statsAprobados, ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="pill pill--warn">Pendientes: <?php echo htmlspecialchars((string) $statsPendientes, ENT_QUOTES, 'UTF-8'); ?></span>
              </div>
              <p class="meta">Los pendientes son los documentos que aún no se han subido.</p>
            </div>
          </div>

          <div class="docs-table-wrapper">
            <table class="docs-table">
              <thead>
                <tr>
                  <th>Documento</th>
                  <th>Estatus</th>
                  <th>Archivo</th>
                  <th style="width: 260px;">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($documentos === []): ?>
                  <tr>
                    <td colspan="4" class="table-empty">No hay documentos registrados para esta empresa.</td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($documentos as $doc): ?>
                    <?php
                    $rowClass = isset($doc['row_class']) ? trim((string) $doc['row_class']) : '';
                    $rowClassAttr = $rowClass !== '' ? ' class="' . htmlspecialchars($rowClass, ENT_QUOTES, 'UTF-8') . '"' : '';
                    $nombre = isset($doc['nombre']) ? (string) $doc['nombre'] : 'Documento';
                    $descripcion = isset($doc['descripcion']) ? trim((string) $doc['descripcion']) : '';
                    $estatusValue = isset($doc['estatus']) ? strtolower((string) $doc['estatus']) : 'pendiente';
                    $estatusLabel = isset($doc['estatus_label']) ? (string) $doc['estatus_label'] : 'Pendiente';
                    $badgeClass = isset($doc['badge_class']) ? (string) $doc['badge_class'] : 'badge pendiente';
                    $ultimaActualizacionLabel = isset($doc['ultima_actualizacion_label']) ? (string) $doc['ultima_actualizacion_label'] : '';
                    $observacion = isset($doc['observacion']) ? trim((string) $doc['observacion']) : '';
                    $origen = isset($doc['origen']) ? (string) $doc['origen'] : 'global';
                    $origenLabel = $origen === 'personalizado' ? 'Personalizado' : 'Global';
                    $obligatorioLabel = isset($doc['obligatorio_label']) ? (string) $doc['obligatorio_label'] : 'No';
                    $obligatorioBadge = isset($doc['obligatorio_badge_class']) ? (string) $doc['obligatorio_badge_class'] : 'badge pendiente';
                    $ruta = isset($doc['ruta']) ? trim((string) $doc['ruta']) : '';
                    $ruta = $ruta !== '' ? $ruta : null;
                    $archivoNombre = isset($doc['archivo_nombre']) ? trim((string) $doc['archivo_nombre']) : '';
                    $uploadLabel = isset($doc['upload_label']) ? (string) $doc['upload_label'] : 'Subir';
                    $documentoId = isset($doc['documento_id']) && $doc['documento_id'] !== null ? (int) $doc['documento_id'] : null;
                    $tipoGlobalId = isset($doc['tipo_id']) && $doc['tipo_id'] !== null ? (int) $doc['tipo_id'] : null;
                    $tipoPersonalizadoId = isset($doc['tipo_personalizado_id']) && $doc['tipo_personalizado_id'] !== null ? (int) $doc['tipo_personalizado_id'] : null;
                    $uploadParams = [];
                    if ($empresaIdQuery !== '') {
                        $uploadParams['empresa_id'] = $empresaIdQuery;
                    }
                    if ($documentoId !== null) {
                        $uploadParams['id'] = (string) $documentoId;
                    } elseif (isset($doc['id']) && $doc['id'] !== null) {
                        $uploadParams['id'] = (string) $doc['id'];
                    }
                    if ($origen === 'global' && $tipoGlobalId !== null) {
                        $uploadParams['tipo_global_id'] = (string) $tipoGlobalId;
                    } elseif ($origen === 'personalizado' && $tipoPersonalizadoId !== null) {
                        $uploadParams['tipo_personalizado_id'] = (string) $tipoPersonalizadoId;
                    }
                    $uploadParams['origen'] = $origen;
                    $uploadUrl = 'empresa_doc_upload.php';
                    if ($uploadParams !== []) {
                        $uploadUrl .= '?' . http_build_query($uploadParams);
                    }
                    if ($origen === 'global') {
                        $uploadUrlParams = [];
                        if ($empresaIdQuery !== '') {
                            $uploadUrlParams['empresa'] = $empresaIdQuery;
                        }
                        $uploadUrlParams['origen'] = 'global';
                        if ($tipoGlobalId !== null) {
                            $uploadUrlParams['tipo'] = (string) $tipoGlobalId;
                        }
                        if ($estatusValue !== '') {
                            $uploadUrlParams['estatus'] = $estatusValue;
                        }
                        $uploadUrl = '../documentos/documento_upload.php';
                        if ($uploadUrlParams !== []) {
                            $uploadUrl .= '?' . http_build_query($uploadUrlParams);
                        }
                    }
                    $detalleUrl = $documentoId !== null ? '../documentos/documento_view.php?id=' . urlencode((string) $documentoId) : null;
                    $reviewUrl = $documentoId !== null ? '../documentos/documento_review.php?id=' . urlencode((string) $documentoId) : null;
                    $editTipoUrl = null;
                    $deleteTipoUrl = null;
                    if ($origen === 'personalizado' && $tipoPersonalizadoId !== null) {
                        $editTipoUrl = 'empresa_documentotipo_edit.php?id=' . urlencode((string) $tipoPersonalizadoId);
                        $deleteTipoUrl = 'empresa_documentotipo_delete.php?id=' . urlencode((string) $tipoPersonalizadoId);
                        if ($empresaIdQuery !== '') {
                            $editTipoUrl .= '&id_empresa=' . urlencode($empresaIdQuery);
                            $deleteTipoUrl .= '&id_empresa=' . urlencode($empresaIdQuery);
                        }
                    }
                    ?>
                    <tr<?php echo $rowClassAttr; ?>>
                      <td>
                        <p class="doc-title"><?php echo htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?></p>
                        <div class="doc-meta">Origen: <?php echo htmlspecialchars($origenLabel, ENT_QUOTES, 'UTF-8'); ?></div>
                        <?php if ($descripcion !== ''): ?>
                          <div class="doc-description"><?php echo htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8'); ?></div>
                        <?php endif; ?>
                        <div class="doc-badges">
                          <span class="<?php echo htmlspecialchars($obligatorioBadge, ENT_QUOTES, 'UTF-8'); ?>">
                            Obligatorio: <?php echo htmlspecialchars($obligatorioLabel, ENT_QUOTES, 'UTF-8'); ?>
                          </span>
                        </div>
                      </td>
                      <td>
                        <span class="<?php echo htmlspecialchars($badgeClass, ENT_QUOTES, 'UTF-8'); ?>">
                          <?php echo htmlspecialchars($estatusLabel, ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                        <?php if ($ultimaActualizacionLabel !== '' && $ultimaActualizacionLabel !== '-'): ?>
                          <div class="doc-meta">Actualizado: <?php echo htmlspecialchars($ultimaActualizacionLabel, ENT_QUOTES, 'UTF-8'); ?></div>
                        <?php endif; ?>
                        <?php if ($observacion !== ''): ?>
                          <div class="doc-description">Observación: <?php echo htmlspecialchars($observacion, ENT_QUOTES, 'UTF-8'); ?></div>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php if ($ruta !== null): ?>
                          <a href="<?php echo htmlspecialchars($ruta, ENT_QUOTES, 'UTF-8'); ?>" class="muted-link" target="_blank" rel="noopener noreferrer">Ver archivo</a>
                          <?php if ($archivoNombre !== ''): ?>
                            <div class="doc-meta"><?php echo htmlspecialchars($archivoNombre, ENT_QUOTES, 'UTF-8'); ?></div>
                          <?php endif; ?>
                        <?php else: ?>
                          <span class="text-muted">Sin archivo</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <div class="actions tight">
                          <?php if ($origen === 'global'): ?>
                            <?php if ($ruta !== null): ?>
                              <a href="<?php echo htmlspecialchars($ruta, ENT_QUOTES, 'UTF-8'); ?>" class="btn small" target="_blank" rel="noopener noreferrer">Ver</a>
                            <?php endif; ?>
                            <?php if (!$empresaIsCompletada && trim($uploadUrl) !== '' && $estatusValue !== 'aprobado' && $estatusValue !== 'revision'): ?>
                              <a href="<?php echo htmlspecialchars($uploadUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn small primary">Subir</a>
                            <?php endif; ?>
                            <?php if ($estatusValue === 'aprobado' && $detalleUrl !== null): ?>
                              <a href="<?php echo htmlspecialchars($detalleUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn small primary">Detalle</a>
                            <?php endif; ?>
                            <?php if (!$empresaIsCompletada && $estatusValue === 'revision' && $reviewUrl !== null): ?>
                              <a href="<?php echo htmlspecialchars($reviewUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn small primary">Revisar</a>
                            <?php endif; ?>
                          <?php else: ?>
                            <?php if (!$empresaIsCompletada): ?>
                              <a href="<?php echo htmlspecialchars($uploadUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn small primary"><?php echo htmlspecialchars($uploadLabel, ENT_QUOTES, 'UTF-8'); ?></a>
                            <?php endif; ?>
                            <?php if ($detalleUrl !== null): ?>
                              <a href="<?php echo htmlspecialchars($detalleUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn small">Detalle</a>
                            <?php endif; ?>
                            <?php if (!$empresaIsCompletada && $reviewUrl !== null && $estatusValue !== 'aprobado'): ?>
                              <a href="<?php echo htmlspecialchars($reviewUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn small">Revisar</a>
                            <?php endif; ?>
                            <?php if (!$empresaIsCompletada && $origen === 'personalizado' && $editTipoUrl !== null): ?>
                              <a href="<?php echo htmlspecialchars($editTipoUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn small">Editar tipo</a>
                              <?php if ($deleteTipoUrl !== null): ?>
                                <a href="<?php echo htmlspecialchars($deleteTipoUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn small danger">Eliminar tipo</a>
                              <?php endif; ?>
                            <?php endif; ?>
                          <?php endif; ?>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <div class="actions footer-actions">
            <?php if ($empresaIdQuery !== ''): ?>
              <a href="<?php echo htmlspecialchars($backUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn secondary">Volver a la empresa</a>
            <?php endif; ?>
            <?php if (!$empresaIsCompletada): ?>
              <a href="<?php echo htmlspecialchars($addDocumentUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn primary">Agregar documento individual</a>
            <?php endif; ?>
          </div>
        </div>
      </section>
    </main>
  </div>
  <script>
    (function () {
      var progressFill = document.querySelector('.progress-fill');
      if (progressFill) {
        var width = progressFill.getAttribute('style');
        if (!width) {
          progressFill.style.width = '<?php echo max(0, min(100, $statsPorcentaje)); ?>%';
        }
      }
    })();
  </script>
</body>

</html>
