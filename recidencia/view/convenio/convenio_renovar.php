<?php
declare(strict_types=1);

$handlerResult = require __DIR__ . '/../../handler/convenio/convenio_renovar_handler.php';

$controllerAvailable = $handlerResult['controllerAvailable'];
$controllerError = $handlerResult['controllerError'];
$errors = $handlerResult['errors'];
$successMessage = $handlerResult['successMessage'];
$formData = $handlerResult['formData'];
$originalConvenio = $handlerResult['originalConvenio'];
$originalMetadata = $handlerResult['originalMetadata'];
$empresaLink = $handlerResult['empresaLink'];
$empresaId = $handlerResult['empresaId'];
$cancelLink = $handlerResult['cancelLink'];
$listLink = $handlerResult['listLink'];
$copyId = $handlerResult['copyId'];
$renewalAllowed = $handlerResult['renewalAllowed'];
$renewalWarning = $handlerResult['renewalWarning'];
$allowedStatuses = $handlerResult['allowedStatuses'];
$newConvenioId = $handlerResult['newConvenioId'];
$newConvenioUrl = $handlerResult['newConvenioUrl'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Renovar Convenio · Residencias Profesionales</title>

  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <link rel="stylesheet" href="../../assets/css/modules/convenio.css" />
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>🔁 Renovar Convenio</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>›</span>
            <a href="<?php echo htmlspecialchars($listLink, ENT_QUOTES, 'UTF-8'); ?>">Convenios</a>
            <span>›</span>
            <span>Renovar</span>
          </nav>
        </div>
        <div class="top-actions" style="display:flex; gap:10px; flex-wrap:wrap;">
          <a href="<?php echo htmlspecialchars($listLink, ENT_QUOTES, 'UTF-8'); ?>" class="btn">📋 Listado</a>
          <?php if ($copyId !== null): ?>
          <a href="convenio_view.php?id=<?php echo urlencode((string) $copyId); ?>" class="btn">👁️ Ver original</a>
          <?php endif; ?>
          <?php if ($empresaLink !== null): ?>
          <a href="<?php echo htmlspecialchars($empresaLink, ENT_QUOTES, 'UTF-8'); ?>" class="btn">🏢 Ver empresa</a>
          <?php endif; ?>
        </div>
      </header>

      <section class="card">
        <header>Información del convenio actual</header>
        <div class="content">
          <p class="text-muted" style="margin-top:-6px">
            Genera una nueva versión del convenio manteniendo el historial intacto. Solo debes definir las nuevas fechas de
            vigencia y, si es necesario, actualizar las observaciones.
          </p>

          <?php if ($controllerError !== null && !$controllerAvailable): ?>
          <div class="alert alert-danger" style="margin-bottom:16px;">
            <?php echo htmlspecialchars($controllerError, ENT_QUOTES, 'UTF-8'); ?>
          </div>
          <?php elseif ($controllerError !== null): ?>
          <div class="alert alert-warning" style="margin-bottom:16px;">
            <?php echo htmlspecialchars($controllerError, ENT_QUOTES, 'UTF-8'); ?>
          </div>
          <?php endif; ?>

          <?php if ($renewalWarning !== null && $controllerAvailable): ?>
          <div class="alert alert-warning" style="margin-bottom:16px;">
            <?php echo htmlspecialchars($renewalWarning, ENT_QUOTES, 'UTF-8'); ?>
            Estados permitidos: <strong><?php echo htmlspecialchars(implode(', ', $allowedStatuses), ENT_QUOTES, 'UTF-8'); ?></strong>.
          </div>
          <?php endif; ?>

          <?php if ($successMessage !== null): ?>
          <div class="alert alert-success" style="margin-bottom:16px;">
            <div><?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php if ($newConvenioUrl !== null): ?>
            <div style="margin-top:6px;">
              <a class="btn" href="<?php echo htmlspecialchars($newConvenioUrl, ENT_QUOTES, 'UTF-8'); ?>">👁️ Ver nueva versión</a>
            </div>
            <?php endif; ?>
          </div>
          <?php endif; ?>

          <?php if ($errors !== []): ?>
          <div class="alert alert-danger" style="margin-bottom:16px;">
            <p style="margin:0 0 8px 0; font-weight:600;">Por favor corrige los siguientes errores:</p>
            <ul style="margin:0 0 0 18px; padding:0;">
              <?php foreach ($errors as $error): ?>
              <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php endif; ?>

          <div class="renewal-info">
            <div class="info-grid">
              <div class="field col-span-2">
                <label>Empresa</label>
                <div>
                  <?php echo htmlspecialchars($originalMetadata['empresaNombre'], ENT_QUOTES, 'UTF-8'); ?>
                  <?php if ($originalMetadata['empresaNumeroControl'] !== ''): ?>
                  <span class="tag">NC: <?php echo htmlspecialchars($originalMetadata['empresaNumeroControl'], ENT_QUOTES, 'UTF-8'); ?></span>
                  <?php endif; ?>
                </div>
              </div>

              <div class="field">
                <label>Convenio original</label>
                <div>#<?php echo htmlspecialchars($originalMetadata['convenioIdLabel'], ENT_QUOTES, 'UTF-8'); ?></div>
              </div>

              <div class="field">
                <label>Folio</label>
                <div><?php echo htmlspecialchars($originalMetadata['folioLabel'], ENT_QUOTES, 'UTF-8'); ?></div>
              </div>

              <div class="field">
                <label>Estatus</label>
                <div>
                  <span class="<?php echo htmlspecialchars($originalMetadata['estatusBadgeClass'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($originalMetadata['estatusLabel'], ENT_QUOTES, 'UTF-8'); ?>
                  </span>
                </div>
              </div>

              <div class="field">
                <label>Tipo de convenio</label>
                <div><?php echo htmlspecialchars($originalMetadata['tipoConvenio'], ENT_QUOTES, 'UTF-8'); ?></div>
              </div>

              <div class="field">
                <label>Responsable académico</label>
                <div><?php echo htmlspecialchars($originalMetadata['responsableAcademico'], ENT_QUOTES, 'UTF-8'); ?></div>
              </div>

              <div class="field">
                <label>Vigencia anterior</label>
                <div><?php echo htmlspecialchars($originalMetadata['vigenciaLabel'], ENT_QUOTES, 'UTF-8'); ?></div>
              </div>

              <div class="field">
                <label>Fecha de inicio</label>
                <div><?php echo htmlspecialchars($originalMetadata['fechaInicioLabel'], ENT_QUOTES, 'UTF-8'); ?></div>
              </div>

              <div class="field">
                <label>Fecha de término</label>
                <div><?php echo htmlspecialchars($originalMetadata['fechaFinLabel'], ENT_QUOTES, 'UTF-8'); ?></div>
              </div>

              <div class="field col-span-2">
                <label>Observaciones registradas</label>
                <div class="observaciones">
                  <?php echo nl2br(htmlspecialchars($originalMetadata['observacionesLabel'], ENT_QUOTES, 'UTF-8')); ?>
                </div>
              </div>
            </div>
          </div>

          <hr class="section-divider" />

          <form class="renewal-form" method="POST" action="">
            <input type="hidden" name="copy_id"
              value="<?php echo $copyId !== null ? htmlspecialchars((string) $copyId, ENT_QUOTES, 'UTF-8') : ''; ?>" />

            <fieldset <?php echo (!$controllerAvailable || !$renewalAllowed) ? 'disabled' : ''; ?>>
              <div class="grid">
                <div class="field">
                  <label for="fecha_inicio" class="required">Nueva fecha de inicio *</label>
                  <input type="date" id="fecha_inicio" name="fecha_inicio" required
                    value="<?php echo htmlspecialchars(convenioRenewalFormValue($formData, 'fecha_inicio'), ENT_QUOTES, 'UTF-8'); ?>" />
                </div>

                <div class="field">
                  <label for="fecha_fin" class="required">Nueva fecha de término *</label>
                  <input type="date" id="fecha_fin" name="fecha_fin" required
                    value="<?php echo htmlspecialchars(convenioRenewalFormValue($formData, 'fecha_fin'), ENT_QUOTES, 'UTF-8'); ?>" />
                </div>

                <div class="field col-span-2">
                  <label for="observaciones">Observaciones</label>
                  <textarea id="observaciones" name="observaciones" rows="3" placeholder="Notas internas de la renovación..."><?php echo htmlspecialchars(convenioRenewalFormValue($formData, 'observaciones'), ENT_QUOTES, 'UTF-8'); ?></textarea>
                  <div class="help">Las observaciones previas se muestran como referencia y puedes actualizarlas para la nueva versión.</div>
                </div>
              </div>
            </fieldset>

            <div class="actions">
              <a href="<?php echo htmlspecialchars($cancelLink, ENT_QUOTES, 'UTF-8'); ?>" class="btn secondary">⬅ Cancelar</a>
              <button type="submit" class="btn primary" <?php echo (!$controllerAvailable || !$renewalAllowed) ? 'disabled' : ''; ?>>💾 Generar nueva versión</button>
            </div>
          </form>
        </div>
      </section>
    </main>
  </div>
</body>

</html>
