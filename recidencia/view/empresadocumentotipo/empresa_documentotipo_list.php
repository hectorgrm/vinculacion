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
$empresaIdQuery = $empresaId !== null ? (string) $empresaId : '';

$statsTotal = (int) ($stats['total'] ?? 0);
$statsSubidos = (int) ($stats['subidos'] ?? 0);
$statsAprobados = (int) ($stats['aprobados'] ?? 0);
$statsPorcentaje = (int) ($stats['porcentaje'] ?? 0);

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

  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/empresas/empresadocs.css" />

  <style>
    .docs-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 10px;
    }

    .docs-summary {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 20px;
      background: #f7f8fa;
      border: 1px solid #ddd;
      border-radius: 10px;
      padding: 15px 20px;
      margin-bottom: 20px;
    }

    .docs-summary strong {
      color: #333;
    }

    .progress-bar {
      width: 200px;
      height: 10px;
      background: #eee;
      border-radius: 5px;
      overflow: hidden;
    }

    .progress-fill {
      height: 10px;
      background: #4caf50;
      width: 0;
      transition: width 0.4s ease;
    }

    .docs-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    .docs-table th,
    .docs-table td {
      padding: 12px 10px;
      border-bottom: 1px solid #e0e0e0;
      text-align: left;
    }

    .docs-table th {
      background: #f1f1f1;
      font-weight: 600;
    }

    .docs-table td span.badge {
      padding: 3px 8px;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 600;
    }

    .docs-table tr.row-custom {
      background: #f9fafc;
    }

    .docs-table tr.row-custom:hover {
      background: #eef3ff;
    }

    .badge.ok {
      background: #c8f7c5;
      color: #2e7d32;
    }

    .badge.pendiente {
      background: #fff3cd;
      color: #856404;
    }

    .badge.rechazado {
      background: #f8d7da;
      color: #721c24;
    }

    .actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 20px;
      flex-wrap: wrap;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #ddd;
      padding: 8px 14px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      color: #222;
      transition: 0.2s;
    }

    .btn:hover {
      background: #ccc;
    }

    .btn.primary {
      background: #007bff;
      color: #fff;
    }

    .btn.primary:hover {
      background: #0069d9;
    }

    .btn.small {
      padding: 6px 10px;
      font-size: 13px;
    }

    .btn.danger {
      background: #f44336;
      color: #fff;
    }

    .btn.danger:hover {
      background: #d32f2f;
    }

    .btn.secondary {
      background: #e0e0e0;
      color: #222;
    }

    .btn.secondary:hover {
      background: #d5d5d5;
    }

    .docs-table tr.section-divider td {
      background: #f9fafc;
      font-weight: 600;
      text-transform: uppercase;
      color: #555;
    }

    .text-muted {
      color: #666;
      font-size: 13px;
    }
  </style>
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>Documentacion de Empresa</h2>
          <p>Control y seguimiento de los requisitos documentales registrados para la empresa seleccionada.</p>
        </div>
        <div class="actions" style="margin-top:0;">
          <?php if ($empresaIdQuery !== ''): ?>
            <a href="<?php echo htmlspecialchars($backUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn secondary">Volver</a>
          <?php endif; ?>
          <a href="<?php echo htmlspecialchars($addDocumentUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn primary">Agregar documento individual</a>
        </div>
      </header>

      <section class="card">
        <header class="docs-header">
          <div>
            <h3>Resumen de documentacion</h3>
            <p class="text-muted">Consulta el estado general de los documentos requeridos.</p>
          </div>
        </header>

        <div class="content">
          <?php if ($errorMessage !== null): ?>
            <div class="alert error" role="alert" style="margin-bottom:16px;">
              <?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>
          <?php if ($statusMessage !== null && $errorMessage === null): ?>
            <div class="alert success" role="status" style="margin-bottom:16px;">
              <?php echo htmlspecialchars($statusMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <div class="docs-summary">
            <div>
              <strong>Empresa:</strong> <?php echo htmlspecialchars($empresaNombre, ENT_QUOTES, 'UTF-8'); ?><br>
              <strong>RFC:</strong> <?php echo htmlspecialchars($empresaRfc, ENT_QUOTES, 'UTF-8'); ?><br>
              <?php if ($regimenFiscal !== ''): ?>
                <strong>Regimen:</strong> <?php echo htmlspecialchars($regimenFiscal, ENT_QUOTES, 'UTF-8'); ?>
              <?php endif; ?>
            </div>

            <div>
              <strong>Documentos subidos:</strong> <?php echo htmlspecialchars((string) $statsSubidos, ENT_QUOTES, 'UTF-8'); ?> /
              <?php echo htmlspecialchars((string) $statsTotal, ENT_QUOTES, 'UTF-8'); ?><br>
              <div class="progress-bar">
                <div class="progress-fill" style="width: <?php echo max(0, min(100, $statsPorcentaje)); ?>%;"></div>
              </div>
              <small><?php echo htmlspecialchars((string) $statsPorcentaje, ENT_QUOTES, 'UTF-8'); ?>% completado
                | <?php echo htmlspecialchars((string) $statsAprobados, ENT_QUOTES, 'UTF-8'); ?> aprobados
              </small>
            </div>
          </div>

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
                  <td colspan="4" style="text-align:center;">No hay documentos registrados para esta empresa.</td>
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
                  $detalleUrl = $documentoId !== null ? '../documentos/documento_view.php?id=' . urlencode((string) $documentoId) : null;
                  $reviewUrl = ($documentoId !== null && $estatusValue !== 'aprobado') ? '../documentos/documento_review.php?id=' . urlencode((string) $documentoId) : null;
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
                      <strong><?php echo htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?></strong>
                      <div class="text-muted" style="font-size:12px; margin-top:4px;">Origen: <?php echo htmlspecialchars($origenLabel, ENT_QUOTES, 'UTF-8'); ?></div>
                      <?php if ($descripcion !== ''): ?>
                        <div class="text-muted" style="margin-top:4px;"><?php echo htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8'); ?></div>
                      <?php endif; ?>
                      <span class="<?php echo htmlspecialchars($obligatorioBadge, ENT_QUOTES, 'UTF-8'); ?>" style="display:inline-block; margin-top:6px;">
                        Obligatorio: <?php echo htmlspecialchars($obligatorioLabel, ENT_QUOTES, 'UTF-8'); ?>
                      </span>
                    </td>
                    <td>
                      <span class="<?php echo htmlspecialchars($badgeClass, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($estatusLabel, ENT_QUOTES, 'UTF-8'); ?>
                      </span>
                      <?php if ($ultimaActualizacionLabel !== '' && $ultimaActualizacionLabel !== '-'): ?>
                        <div class="text-muted" style="margin-top:4px;">Actualizado: <?php echo htmlspecialchars($ultimaActualizacionLabel, ENT_QUOTES, 'UTF-8'); ?></div>
                      <?php endif; ?>
                      <?php if ($observacion !== ''): ?>
                        <div class="text-muted" style="margin-top:4px;">Observacion: <?php echo htmlspecialchars($observacion, ENT_QUOTES, 'UTF-8'); ?></div>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php if ($ruta !== null): ?>
                        <a href="<?php echo htmlspecialchars($ruta, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">Ver</a>
                        <?php if ($archivoNombre !== ''): ?>
                          <div class="text-muted" style="margin-top:4px;"><?php echo htmlspecialchars($archivoNombre, ENT_QUOTES, 'UTF-8'); ?></div>
                        <?php endif; ?>
                      <?php else: ?>
                        <span class="text-muted">Sin archivo</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <div class="actions" style="justify-content:flex-start; margin-top:0;">
                        <a href="<?php echo htmlspecialchars($uploadUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn small primary"><?php echo htmlspecialchars($uploadLabel, ENT_QUOTES, 'UTF-8'); ?></a>
                        <?php if ($detalleUrl !== null): ?>
                          <a href="<?php echo htmlspecialchars($detalleUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn small">Detalle</a>
                        <?php endif; ?>
                        <?php if ($reviewUrl !== null): ?>
                          <a href="<?php echo htmlspecialchars($reviewUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn small">Revisar</a>
                        <?php endif; ?>
                        <?php if ($origen === 'personalizado' && $editTipoUrl !== null): ?>
                          <a href="<?php echo htmlspecialchars($editTipoUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn small">Editar tipo</a>
                          <?php if ($deleteTipoUrl !== null): ?>
                            <a href="<?php echo htmlspecialchars($deleteTipoUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn small danger">Eliminar tipo</a>
                          <?php endif; ?>
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>

          <div class="actions" style="justify-content:flex-end;">
            <?php if ($empresaIdQuery !== ''): ?>
              <a href="<?php echo htmlspecialchars($backUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn secondary">Volver a la empresa</a>
            <?php endif; ?>
            <a href="<?php echo htmlspecialchars($addDocumentUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn primary">Agregar documento individual</a>
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
