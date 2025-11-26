<?php
declare(strict_types=1);

$handlerResult = require __DIR__ . '/../../handler/convenio/convenio_edit_handler.php';

$estatusOptions = $handlerResult['estatusOptions'];
$controllerError = $handlerResult['controllerError'];
$controllerAvailable = $handlerResult['controllerAvailable'];
$errors = $handlerResult['errors'];
$successMessage = $handlerResult['successMessage'];
$convenio = $handlerResult['convenio'];
$convenioId = $handlerResult['convenioId'];
$formData = $handlerResult['formData'];
$empresaLabel = $handlerResult['empresaLabel'];
$borradorUrl = $handlerResult['borradorUrl'];
$borradorFileName = $handlerResult['borradorFileName'];
$folioLabel = $handlerResult['folioLabel'];
$empresaLink = $handlerResult['empresaLink'];
$convenioListLink = $handlerResult['convenioListLink'];
$machoteLink = $handlerResult['machoteLink'];
$cancelLink = $handlerResult['cancelLink'];

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Convenio · Residencias Profesionales</title>

  <!-- Estilos completos para esta vista -->
  <link rel="stylesheet" href="../../assets/css/modules/convenio/convenioedit.css" />
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>✏️ Editar Convenio</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>›</span>
            <a href="convenio_list.php">Convenios</a>
            <span>›</span>
            <span>Editar</span>
          </nav>
        </div>
        <div class="top-actions" style="display:flex; gap:10px; flex-wrap:wrap;">
          <?php if ($convenioId > 0): ?>
          <a href="convenio_view.php?id=<?php echo urlencode((string) $convenioId); ?>" class="btn">👁️ Ver</a>
          <?php endif; ?>
          <?php if ($borradorUrl !== null): ?>
          <a href="<?php echo htmlspecialchars($borradorUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn" target="_blank">📄 Ver
            archivo</a>
          <?php endif; ?>
          <a href="convenio_list.php" class="btn">⬅ Volver</a>
        </div>
      </header>

      <section class="card">
        <header>🧾 Datos del Convenio</header>
        <div class="content">
          <p class="text-muted" style="margin-top:-6px">
            <?php if ($convenio !== null): ?>
            Estás editando el convenio <strong>#<?php echo htmlspecialchars((string) $convenioId, ENT_QUOTES, 'UTF-8'); ?></strong>
            vinculado a la empresa
            <strong><?php echo htmlspecialchars($empresaLabel, ENT_QUOTES, 'UTF-8'); ?></strong>. Folio actual:
            <strong><?php echo htmlspecialchars($folioLabel, ENT_QUOTES, 'UTF-8'); ?></strong>.
            <?php else: ?>
            Para editar un convenio selecciona un registro válido desde el listado.
            <?php endif; ?>
          </p>

          <?php if ($controllerError !== null && !$controllerAvailable): ?>
          <div class="alert alert-danger" style="margin-bottom:16px;">
            <?php echo htmlspecialchars($controllerError, ENT_QUOTES, 'UTF-8'); ?>
          </div>
          <?php endif; ?>

          <?php if ($successMessage !== null): ?>
          <div class="alert alert-success" style="margin-bottom:16px;">
            <?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?>
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

          <?php if ($convenio !== null): ?>
          <form class="form" method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="id"
              value="<?php echo htmlspecialchars((string) $convenioId, ENT_QUOTES, 'UTF-8'); ?>" />
            <input type="hidden" name="empresa_id"
              value="<?php echo htmlspecialchars(convenioFormValue($formData, 'empresa_id'), ENT_QUOTES, 'UTF-8'); ?>" />

            <div class="grid">
              <div class="field col-span-2">
                <label for="empresa_locked">Empresa</label>
                <input type="text" id="empresa_locked"
                  value="<?php echo htmlspecialchars($empresaLabel, ENT_QUOTES, 'UTF-8'); ?>" disabled />
                <div class="help">La empresa no puede cambiarse desde aquí.</div>
              </div>

              <div class="field">
                <label for="folio">Folio</label>
                <input type="text" id="folio" name="folio" placeholder="Ej: CBR-2025-01"
                  value="<?php echo htmlspecialchars(convenioFormValue($formData, 'folio'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="estatus" class="required">Estatus *</label>
                <select id="estatus" name="estatus" required>
                  <?php foreach ($estatusOptions as $option): ?>
                  <option value="<?php echo htmlspecialchars($option, ENT_QUOTES, 'UTF-8'); ?>"
                    <?php echo convenioFormValue($formData, 'estatus') === $option ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($option, ENT_QUOTES, 'UTF-8'); ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="field">
                <label for="tipo_convenio">Tipo de convenio</label>
                <input type="text" id="tipo_convenio" name="tipo_convenio" placeholder="Ej: convenio marco"
                  value="<?php echo htmlspecialchars(convenioFormValue($formData, 'tipo_convenio'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="responsable_academico">Responsable académico</label>
                <input type="text" id="responsable_academico" name="responsable_academico" placeholder="Ej: Mtra. Ana Pérez"
                  value="<?php echo htmlspecialchars(convenioFormValue($formData, 'responsable_academico'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="fecha_inicio">Fecha de inicio</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio"
                  value="<?php echo htmlspecialchars(convenioFormValue($formData, 'fecha_inicio'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="fecha_fin">Fecha de término</label>
                <input type="date" id="fecha_fin" name="fecha_fin"
                  value="<?php echo htmlspecialchars(convenioFormValue($formData, 'fecha_fin'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label>Archivo actual (PDF)</label>
                <div>
                  <?php if ($borradorUrl !== null): ?>
                  <a class="btn" href="<?php echo htmlspecialchars($borradorUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank">📄 Ver
                    archivo actual</a>
                  <?php if ($borradorFileName !== null): ?>
                  <div class="help">Archivo guardado: <?php echo htmlspecialchars($borradorFileName, ENT_QUOTES, 'UTF-8'); ?></div>
                  <?php endif; ?>
                  <?php else: ?>
                  <div class="help">No se ha cargado un archivo para este convenio.</div>
                  <?php endif; ?>
                </div>
              </div>

              <div class="field">
                <label for="borrador_path">Reemplazar PDF</label>
                <input type="file" id="borrador_path" name="borrador_path" accept="application/pdf" />
                <div class="help">Sube un archivo únicamente si deseas reemplazar el documento actual.</div>
              </div>

              <div class="field col-span-2">
                <label for="observaciones">Notas / Observaciones</label>
                <textarea id="observaciones" name="observaciones" rows="4"
                  placeholder="Comentarios internos del área de vinculación..."><?php echo htmlspecialchars(convenioFormValue($formData, 'observaciones'), ENT_QUOTES, 'UTF-8'); ?></textarea>
              </div>
            </div>

            <div class="actions">
              <a href="<?php echo htmlspecialchars($cancelLink, ENT_QUOTES, 'UTF-8'); ?>" class="btn">⬅ Cancelar</a>
              <button type="submit" class="btn primary">💾 Guardar Cambios</button>
            </div>
          </form>
          <?php else: ?>
          <p style="margin:16px 0 0 0;">No fue posible cargar la información del convenio. Regresa al listado e intenta
            nuevamente.</p>
          <?php endif; ?>
        </div>
      </section>

      <section class="card">
        <header>Accesos rápidos</header>
        <div class="content actions" style="justify-content:flex-start; flex-wrap:wrap; gap:8px;">
          <a class="btn" href="<?php echo htmlspecialchars($empresaLink, ENT_QUOTES, 'UTF-8'); ?>">🏢 Ver empresa</a>
          <a class="btn" href="<?php echo htmlspecialchars($convenioListLink, ENT_QUOTES, 'UTF-8'); ?>">📑 Ver convenios de esta
            empresa</a>
          <a class="btn" href="<?php echo htmlspecialchars($machoteLink, ENT_QUOTES, 'UTF-8'); ?>">📝 Revisar machote</a>
        </div>
      </section>
    </main>
  </div>
</body>

</html>
