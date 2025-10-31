<?php
declare(strict_types=1);

/** @var array{
 *     empresaId: ?int,
 *     empresa: ?array<string, mixed>,
 *     globalDocuments: array<int, array<string, mixed>>,
 *     customDocuments: array<int, array<string, mixed>>,
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

if (!function_exists('empresaDocTipoBuildFileUrl')) {
    /**
     * @param string|null $ruta
     */
    function empresaDocTipoBuildFileUrl(?string $ruta): ?string
    {
        if ($ruta === null) {
            return null;
        }

        $ruta = trim($ruta);
        if ($ruta === '') {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $ruta) === 1) {
            return $ruta;
        }

        $sanitized = str_replace('\\', '/', $ruta);

        return '../../' . ltrim($sanitized, '/');
    }
}
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
                <th>Documento requerido</th>
                <th>Estado</th>
                <th>Archivo</th>
                <th style="width: 260px;">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($globalDocuments === [] && $customDocuments === []): ?>
                <tr>
                  <td colspan="4" style="text-align:center;">No hay documentos registrados para esta empresa.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($globalDocuments as $documento): ?>
                  <?php
                  $docId = $documento['id'] ?? null;
                  $docNombre = isset($documento['nombre']) ? (string) $documento['nombre'] : 'Documento';
                  $estadoClass = isset($documento['estado_badge_class']) ? (string) $documento['estado_badge_class'] : 'badge pendiente';
                  $estadoLabel = isset($documento['estado_label']) ? (string) $documento['estado_label'] : 'Pendiente';
                  $archivoNombre = isset($documento['archivo_nombre']) ? (string) $documento['archivo_nombre'] : '';
                  $archivoRuta = isset($documento['archivo_ruta']) ? (string) $documento['archivo_ruta'] : null;
                  $archivoUrl = empresaDocTipoBuildFileUrl($archivoRuta);
                  $documentoId = isset($documento['documento_id']) ? (int) $documento['documento_id'] : null;
                  $ultimaActualizacion = isset($documento['ultima_actualizacion_label']) ? (string) $documento['ultima_actualizacion_label'] : '-';
                  $uploadUrl = '../documentos/documento_upload.php';
                  $uploadUrl .= '?empresa=' . urlencode($empresaIdQuery !== '' ? $empresaIdQuery : '0');
                  if ($docId !== null) {
                      $uploadUrl .= '&tipo=' . urlencode((string) $docId);
                  }
                  $viewUrl = $documentoId !== null
                      ? '../documentos/documento_view.php?id=' . urlencode((string) $documentoId)
                      : null;
                  ?>
                  <tr>
                    <td>
                      <strong><?php echo htmlspecialchars($docNombre, ENT_QUOTES, 'UTF-8'); ?></strong><br>
                      <?php if (!empty($documento['descripcion'])): ?>
                        <span class="text-muted"><?php echo htmlspecialchars((string) $documento['descripcion'], ENT_QUOTES, 'UTF-8'); ?></span><br>
                      <?php endif; ?>
                      <span class="<?php echo htmlspecialchars((string) ($documento['obligatorio_badge_class'] ?? 'badge pendiente'), ENT_QUOTES, 'UTF-8'); ?>">
                        Obligatorio: <?php echo htmlspecialchars((string) ($documento['obligatorio_label'] ?? 'No'), ENT_QUOTES, 'UTF-8'); ?>
                      </span>
                    </td>
                    <td>
                      <span class="<?php echo htmlspecialchars($estadoClass, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($estadoLabel, ENT_QUOTES, 'UTF-8'); ?>
                      </span>
                      <?php if ($ultimaActualizacion !== '-'): ?>
                        <div class="text-muted" style="margin-top:4px;">Actualizado: <?php echo htmlspecialchars($ultimaActualizacion, ENT_QUOTES, 'UTF-8'); ?></div>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php if ($archivoUrl !== null && $archivoNombre !== ''): ?>
                        <a href="<?php echo htmlspecialchars($archivoUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">
                          <?php echo htmlspecialchars($archivoNombre, ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                      <?php else: ?>
                        <span class="text-muted">Sin archivo</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <div class="actions" style="justify-content:flex-start; margin-top:0;">
                        <a href="<?php echo htmlspecialchars($uploadUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn small primary">
                          <?php echo $documentoId === null ? 'Subir' : 'Actualizar'; ?>
                        </a>
                        <?php if ($viewUrl !== null): ?>
                          <a href="<?php echo htmlspecialchars($viewUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn small">Revisar</a>
                        <?php endif; ?>
                        <?php if (!empty($documento['observacion'])): ?>
                          <span class="text-muted" style="align-self:center;"><?php echo htmlspecialchars((string) $documento['observacion'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>

                <?php if ($customDocuments !== []): ?>
                  <tr class="section-divider">
                    <td colspan="4">Documentos individuales de la empresa</td>
                  </tr>
                  <?php foreach ($customDocuments as $documento): ?>
                    <?php
                    $customId = isset($documento['id']) ? (int) $documento['id'] : 0;
                    $docNombre = isset($documento['nombre']) ? (string) $documento['nombre'] : 'Documento individual';
                    $estadoClass = isset($documento['estado_badge_class']) ? (string) $documento['estado_badge_class'] : 'badge pendiente';
                    $estadoLabel = isset($documento['estado_label']) ? (string) $documento['estado_label'] : 'Sin registro';
                    $editarUrl = 'empresa_documentotipo_edit.php?id=' . urlencode((string) $customId);
                    $eliminarUrl = 'empresa_documentotipo_delete.php?id=' . urlencode((string) $customId);
                    if ($empresaIdQuery !== '') {
                        $editarUrl .= '&id_empresa=' . urlencode($empresaIdQuery);
                        $eliminarUrl .= '&id_empresa=' . urlencode($empresaIdQuery);
                    }
                    ?>
                    <tr class="empresa-doc">
                      <td>
                        <strong><?php echo htmlspecialchars($docNombre, ENT_QUOTES, 'UTF-8'); ?></strong><br>
                        <?php if (!empty($documento['descripcion'])): ?>
                          <span class="text-muted"><?php echo htmlspecialchars((string) $documento['descripcion'], ENT_QUOTES, 'UTF-8'); ?></span><br>
                        <?php endif; ?>
                        <span class="<?php echo htmlspecialchars((string) ($documento['obligatorio_badge_class'] ?? 'badge pendiente'), ENT_QUOTES, 'UTF-8'); ?>">
                          Obligatorio: <?php echo htmlspecialchars((string) ($documento['obligatorio_label'] ?? 'No'), ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                      </td>
                      <td>
                        <span class="<?php echo htmlspecialchars($estadoClass, ENT_QUOTES, 'UTF-8'); ?>">
                          <?php echo htmlspecialchars($estadoLabel, ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                        <?php if (!empty($documento['creado_en_label']) && $documento['creado_en_label'] !== '-'): ?>
                          <div class="text-muted" style="margin-top:4px;">Registrado: <?php echo htmlspecialchars((string) $documento['creado_en_label'], ENT_QUOTES, 'UTF-8'); ?></div>
                        <?php endif; ?>
                      </td>
                      <td><span class="text-muted">Pendiente de carga</span></td>
                      <td>
                        <div class="actions" style="justify-content:flex-start; margin-top:0;">
                          <a href="<?php echo htmlspecialchars($editarUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn small">Editar</a>
                          <a href="<?php echo htmlspecialchars($eliminarUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn small danger">Eliminar</a>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
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
