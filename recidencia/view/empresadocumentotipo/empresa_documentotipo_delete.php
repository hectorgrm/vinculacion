<?php
declare(strict_types=1);

/** @var array{
 *     empresaId: ?int,
 *     documentoId: ?int,
 *     empresa: ?array<string, mixed>,
 *     documento: ?array<string, mixed>,
 *     usageCount: int,
 *     supportsActivo: bool,
 *     controllerError: ?string,
 *     inputError: ?string,
 *     notFoundMessage: ?string,
 *     errorMessage: ?string,
 *     statusMessage: ?string
 * } $handlerResult
 */
$handlerResult = require __DIR__ . '/../../handler/empresadocumentotipo/empresa_documentotipo_delete_handler.php';

$empresaId = $handlerResult['empresaId'];
$documentoId = $handlerResult['documentoId'];
$empresa = $handlerResult['empresa'];
$documento = $handlerResult['documento'];
$usageCount = (int) ($handlerResult['usageCount'] ?? 0);
$supportsActivo = (bool) ($handlerResult['supportsActivo'] ?? false);
$controllerError = $handlerResult['controllerError'];
$inputError = $handlerResult['inputError'];
$notFoundMessage = $handlerResult['notFoundMessage'];
$errorMessage = $handlerResult['errorMessage'];
$statusMessage = $handlerResult['statusMessage'];

$empresaNombre = is_array($empresa) ? (string) ($empresa['nombre_label'] ?? ($empresa['nombre'] ?? '')) : '';
$empresaRfc = is_array($empresa) ? (string) ($empresa['rfc_label'] ?? ($empresa['rfc'] ?? '')) : '';
$empresaRegimen = is_array($empresa) ? (string) ($empresa['regimen_label'] ?? ($empresa['regimen_fiscal'] ?? '')) : '';

$documentoNombre = is_array($documento) ? (string) ($documento['nombre_label'] ?? ($documento['nombre'] ?? 'Documento individual')) : 'Documento individual';
$documentoDescripcion = is_array($documento) ? (string) ($documento['descripcion_label'] ?? ($documento['descripcion'] ?? '')) : '';
$obligatorioLabel = is_array($documento) ? (string) ($documento['obligatorio_label'] ?? 'No') : 'No';
$obligatorioClass = is_array($documento) ? (string) ($documento['obligatorio_badge_class'] ?? 'badge pendiente') : 'badge pendiente';
$estadoLabel = is_array($documento) ? (string) ($documento['estado_label'] ?? 'Sin estatus') : 'Sin estatus';
$estadoClass = is_array($documento) ? (string) ($documento['estado_badge_class'] ?? 'badge pendiente') : 'badge pendiente';
$tipoEmpresaLabel = is_array($documento) ? (string) ($documento['tipo_empresa_label'] ?? 'No especificado') : 'No especificado';
$creadoEnLabel = is_array($documento) ? (string) ($documento['creado_en_label'] ?? '') : '';

$listUrl = 'empresa_documentotipo_list.php';
if ($empresaId !== null) {
    $listUrl .= '?id_empresa=' . urlencode((string) $empresaId);
}

$deleteAction = '../../handler/empresadocumentotipo/empresa_documentotipo_delete_action.php';
$usageMessage = empresaDocumentoTipoDeleteUsageMessage($usageCount);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Eliminar documento individual - Residencias Profesionales</title>

  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/modules/documentotipo.css" />
  <link rel="stylesheet" href="../../assets/css/modules/empresa.css" />

  <style>
    .danger-zone {
      background: #fff;
      border: 1px solid #fee2e2;
      border-radius: 18px;
      box-shadow: var(--shadow-sm);
    }

    .danger-zone > header {
      padding: 16px 20px;
      border-bottom: 1px solid #fee2e2;
      font-weight: 700;
      color: #b91c1c;
    }

    .danger-zone .content {
      padding: 20px;
    }

    .summary {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 12px;
      background: #f9fafb;
      border: 1px solid #eef2f7;
      border-radius: 10px;
      padding: 16px;
      margin-bottom: 16px;
    }

    .badge.warn {
      background: #fff3cd;
      color: #856404;
    }

    .note {
      border-left: 4px solid #f97316;
      background: #fff7ed;
      padding: 12px 16px;
      border-radius: 6px;
      margin-top: 16px;
    }
  </style>
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>Eliminar / Desactivar documento individual</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a><span>&rsaquo;</span>
            <a href="<?php echo htmlspecialchars($listUrl, ENT_QUOTES, 'UTF-8'); ?>">Documentos de empresa</a><span>&rsaquo;</span>
            <span>Eliminar</span>
          </nav>
        </div>
        <a class="btn" href="<?php echo htmlspecialchars($listUrl, ENT_QUOTES, 'UTF-8'); ?>">&laquo; Volver</a>
      </header>

      <?php if ($controllerError !== null): ?>
        <section class="card">
          <header>Aviso</header>
          <div class="content">
            <div class="alert alert-danger">
              <?php echo htmlspecialchars($controllerError, ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <p class="text-muted" style="margin:0;">Regresa al listado e intenta nuevamente.</p>
          </div>
        </section>
      <?php else: ?>
        <?php if ($statusMessage !== null): ?>
          <section class="card">
            <div class="content">
              <div class="alert alert-success" style="margin:0;">
                <?php echo htmlspecialchars($statusMessage, ENT_QUOTES, 'UTF-8'); ?>
              </div>
            </div>
          </section>
        <?php endif; ?>

        <?php if ($errorMessage !== null): ?>
          <section class="card">
            <div class="content">
              <div class="alert alert-danger" style="margin:0;">
                <?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
              </div>
            </div>
          </section>
        <?php endif; ?>

        <?php if ($inputError !== null): ?>
          <section class="card">
            <div class="content">
              <div class="alert alert-danger" style="margin:0;">
                <?php echo htmlspecialchars($inputError, ENT_QUOTES, 'UTF-8'); ?>
              </div>
            </div>
          </section>
        <?php endif; ?>

        <?php if ($notFoundMessage !== null): ?>
          <section class="card">
            <header>No se encontr&oacute; el registro</header>
            <div class="content">
              <p class="text-muted" style="margin-bottom:0;">
                <?php echo htmlspecialchars($notFoundMessage, ENT_QUOTES, 'UTF-8'); ?>
              </p>
            </div>
          </section>
        <?php elseif ($documento !== null): ?>
          <section class="card">
            <header>Empresa</header>
            <div class="content">
              <?php if ($empresa !== null): ?>
                <div class="summary">
                  <div><strong>Nombre:</strong> <?php echo htmlspecialchars($empresaNombre !== '' ? $empresaNombre : 'Sin nombre', ENT_QUOTES, 'UTF-8'); ?></div>
                  <div><strong>RFC:</strong> <?php echo htmlspecialchars($empresaRfc !== '' ? $empresaRfc : 'Sin RFC', ENT_QUOTES, 'UTF-8'); ?></div>
                  <div><strong>R&eacute;gimen:</strong> <?php echo htmlspecialchars($empresaRegimen !== '' ? $empresaRegimen : 'Sin dato', ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
              <?php else: ?>
                <p class="text-muted" style="margin:0;">No se pudieron cargar los datos de la empresa.</p>
              <?php endif; ?>
            </div>
          </section>

          <section class="danger-zone" style="margin-top:20px;">
            <header>Confirmaci&oacute;n requerida</header>
            <div class="content">
              <p>
                Est&aacute;s a punto de eliminar o desactivar el documento individual
                <strong><?php echo htmlspecialchars($documentoNombre, ENT_QUOTES, 'UTF-8'); ?></strong>.
              </p>

              <div class="summary">
                <div>
                  <strong>Descripci&oacute;n</strong><br>
                  <span class="text-muted"><?php echo htmlspecialchars($documentoDescripcion !== '' ? $documentoDescripcion : 'Sin descripci&oacute;n', ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
                <div>
                  <strong>Obligatorio</strong><br>
                  <span class="<?php echo htmlspecialchars($obligatorioClass, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($obligatorioLabel, ENT_QUOTES, 'UTF-8'); ?>
                  </span>
                </div>
                <div>
                  <strong>Estatus</strong><br>
                  <span class="<?php echo htmlspecialchars($estadoClass, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($estadoLabel, ENT_QUOTES, 'UTF-8'); ?>
                  </span>
                </div>
                <div>
                  <strong>Tipo de empresa</strong><br>
                  <span class="text-muted"><?php echo htmlspecialchars($tipoEmpresaLabel, ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
                <?php if ($creadoEnLabel !== ''): ?>
                  <div>
                    <strong>Registrado el</strong><br>
                    <span class="text-muted"><?php echo htmlspecialchars($creadoEnLabel, ENT_QUOTES, 'UTF-8'); ?></span>
                  </div>
                <?php endif; ?>
              </div>

              <div class="note">
                <p style="margin:0 0 6px 0;"><strong>Archivos vinculados:</strong> <?php echo htmlspecialchars($usageMessage, ENT_QUOTES, 'UTF-8'); ?></p>
                <p style="margin:0;">
                  Si existen archivos cargados, el documento se marcar&aacute; como <em>inactivo</em><?php if (!$supportsActivo): ?>;
                  verifica que la tabla <code>rp_documento_tipo_empresa</code> cuente con la columna <code>activo</code> para permitir la desactivaci&oacute;n<?php endif; ?>.
                </p>
              </div>

              <form action="<?php echo htmlspecialchars($deleteAction, ENT_QUOTES, 'UTF-8'); ?>" method="post" style="margin-top:20px;">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars((string) $documentoId, ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="empresa_id" value="<?php echo htmlspecialchars((string) $empresaId, ENT_QUOTES, 'UTF-8'); ?>">

                <label style="display:flex; gap:8px; align-items:flex-start; margin:12px 0;">
                  <input type="checkbox" name="confirm" required>
                  <span>Confirmo que deseo eliminar o desactivar este documento individual para la empresa seleccionada.</span>
                </label>

                <div class="actions" style="justify-content:flex-end;">
                  <a class="btn" href="<?php echo htmlspecialchars($listUrl, ENT_QUOTES, 'UTF-8'); ?>">Cancelar</a>
                  <button class="btn danger" type="submit">
                    <?php echo $usageCount > 0 ? 'Desactivar' : 'Eliminar'; ?>
                  </button>
                </div>
              </form>
            </div>
          </section>
        <?php endif; ?>
      <?php endif; ?>
    </main>
  </div>
</body>
</html>
