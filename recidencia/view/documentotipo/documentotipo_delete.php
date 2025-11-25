<?php
declare(strict_types=1);

/** @var array{
 *     documentoTipoId: ?int,
 *     documentoTipo: ?array<string, mixed>,
 *     controllerError: ?string,
 *     notFoundMessage: ?string,
 *     errorMessage: ?string,
 *     statusMessage: ?string
 * } $handlerResult
 */
$handlerResult = require __DIR__ . '/../../handler/documentotipo/documentotipo_delete_handler.php';

$documentoTipoId = $handlerResult['documentoTipoId'];
$documentoTipo = $handlerResult['documentoTipo'];
$controllerError = $handlerResult['controllerError'];
$notFoundMessage = $handlerResult['notFoundMessage'];
$errorMessage = $handlerResult['errorMessage'];
$statusMessage = $handlerResult['statusMessage'];

$nombre = $documentoTipo['nombre_label'] ?? 'Tipo de documento';
$descripcion = $documentoTipo['descripcion_label'] ?? 'Sin descripcion';
$tipoEmpresaLabel = $documentoTipo['tipo_empresa_label'] ?? 'Sin especificar';
$obligatorioLabel = $documentoTipo['obligatorio_label'] ?? 'Sin dato';
$obligatorioClass = $documentoTipo['obligatorio_class'] ?? 'badge secondary';
$activo = $documentoTipo['activo'] ?? true;
$activoLabel = $documentoTipo['activo_label'] ?? ($activo ? 'Activo' : 'Inactivo');
$activoClass = $activo ? 'badge ok' : 'badge warn';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Eliminar Tipo de Documento - Residencias</title>

  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <link rel="stylesheet" href="../../assets/css/modules/documentotipo.css" />
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>Eliminar / Desactivar Tipo de Documento</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a><span>&rsaquo;</span>
            <a href="documentotipo_list.php">Tipos de Documento</a><span>&rsaquo;</span>
            <span>Eliminar</span>
          </nav>
        </div>
        <a class="btn" href="documentotipo_list.php">&laquo; Volver</a>
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

        <?php if ($notFoundMessage !== null): ?>
          <section class="card">
            <header>No se encontr&oacute; el registro</header>
            <div class="content">
              <p class="text-muted" style="margin-bottom:0;">
                <?php echo htmlspecialchars($notFoundMessage, ENT_QUOTES, 'UTF-8'); ?>
              </p>
            </div>
          </section>
        <?php else: ?>
          <section class="danger-zone">
            <header>Confirmaci&oacute;n requerida</header>
            <div class="content">
              <p>
                Est&aacute;s a punto de eliminar o desactivar el tipo de documento:
              </p>
              <p style="font-size:1.1em; margin:0 0 12px 0;">
                <strong>#<?php echo htmlspecialchars((string) $documentoTipoId, ENT_QUOTES, 'UTF-8'); ?></strong>
                &mdash;
                <em><?php echo htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?></em>
              </p>

              <div class="card" style="margin-bottom:16px;">
                <header>Detalle del documento</header>
                <div class="content">
                  <p class="text-muted"><strong>Descripci&oacute;n:</strong> <?php echo htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8'); ?></p>
                  <p class="text-muted"><strong>Tipo de empresa:</strong> <?php echo htmlspecialchars($tipoEmpresaLabel, ENT_QUOTES, 'UTF-8'); ?></p>
                  <p class="text-muted">
                    <strong>Obligatorio:</strong>
                    <span class="<?php echo htmlspecialchars($obligatorioClass, ENT_QUOTES, 'UTF-8'); ?>">
                      <?php echo htmlspecialchars($obligatorioLabel, ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                  </p>
                  <p class="text-muted">
                    <strong>Estatus actual:</strong>
                    <span class="<?php echo htmlspecialchars($activoClass, ENT_QUOTES, 'UTF-8'); ?>">
                      <?php echo htmlspecialchars($activoLabel, ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                  </p>
                </div>
              </div>

              <p class="text-muted">
                Si este tipo ya est&aacute; asignado a alg&uacute;n documento de empresa se marcar&aacute; como
                <em>inactivo</em> y dejar&aacute; de aparecer en nuevos registros. Si no est&aacute; en uso, se
                eliminar&aacute; permanentemente de la base de datos.
              </p>

              <form action="../../handler/documentotipo/documentotipo_delete_action.php" method="post" style="margin-top:16px;">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars((string) $documentoTipoId, ENT_QUOTES, 'UTF-8'); ?>" />

                <label style="display:flex; gap:8px; align-items:flex-start; margin:12px 0;">
                  <input type="checkbox" name="confirm" required />
                  <span>Confirmo que deseo proceder con la eliminaci&oacute;n o desactivaci&oacute;n de este tipo de documento.</span>
                </label>

                <div class="actions" style="justify-content:flex-end;">
                  <a class="btn" href="documentotipo_list.php">Cancelar</a>
                  <button class="btn danger" type="submit">Continuar</button>
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
