<?php
declare(strict_types=1);

require_once __DIR__ . '/../../controller/ConvenioController.php';
require_once __DIR__ . '/../../common/functions/conveniofunction.php';
require_once __DIR__ . '/../../common/functions/convenio/conveniofunctions_edit.php';

$estatusOptions = convenioStatusOptions();
$controllerData = convenioResolveControllerData();
$controller = $controllerData['controller'];
$controllerError = $controllerData['error'];

$convenioId = 0;
if (isset($_GET['id'])) {
    $filtered = preg_replace('/[^0-9]/', '', (string) $_GET['id']);
    $convenioId = $filtered !== null && $filtered !== '' ? (int) $filtered : 0;
}

if ($convenioId <= 0 && isset($_POST['id'])) {
    $filtered = preg_replace('/[^0-9]/', '', (string) $_POST['id']);
    $convenioId = $filtered !== null && $filtered !== '' ? (int) $filtered : 0;
}

$errors = [];
$successMessage = null;
$convenio = null;

if ($controller === null) {
    $errors[] = $controllerError ?? 'No se pudo establecer conexión con la base de datos. Intenta nuevamente más tarde.';
} elseif ($convenioId <= 0) {
    $errors[] = 'El identificador del convenio no es válido.';
} else {
    try {
        $convenio = $controller->getConvenioById($convenioId);

        if ($convenio === null) {
            $errors[] = 'El convenio solicitado no existe o fue eliminado.';
        }
    } catch (\RuntimeException $runtimeException) {
        $errors[] = $runtimeException->getMessage();
    }
}

$formData = $convenio !== null
    ? convenioHydrateFormDataFromRecord($convenio)
    : convenioFormDefaults();

if ($controller !== null && $convenio !== null && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $handleResult = convenioHandleEditRequest(
        $controller,
        isset($convenio['id']) ? (int) $convenio['id'] : $convenioId,
        $_POST,
        $_FILES,
        $convenio,
        convenioUploadsAbsoluteDir(),
        convenioUploadsRelativeDir()
    );

    $formData = $handleResult['formData'];
    $successMessage = $handleResult['successMessage'];
    $postErrors = $handleResult['errors'];

    if ($postErrors !== []) {
        $errors = array_merge($errors, $postErrors);
    }

    if (is_array($handleResult['convenio'])) {
        $convenio = $handleResult['convenio'];
        if (isset($convenio['id']) && ctype_digit((string) $convenio['id'])) {
            $convenioId = (int) $convenio['id'];
        }
    }
}

if ($errors !== []) {
    $errors = array_values(array_unique($errors));
}

$empresaNombre = $convenio !== null && isset($convenio['empresa_nombre'])
    ? trim((string) $convenio['empresa_nombre'])
    : '';
$empresaNumeroControl = $convenio !== null && isset($convenio['empresa_numero_control'])
    ? trim((string) $convenio['empresa_numero_control'])
    : '';
$empresaId = $convenio !== null && isset($convenio['empresa_id'])
    ? (int) $convenio['empresa_id']
    : 0;

$empresaLabel = $empresaNombre !== '' ? $empresaNombre : ($empresaId > 0 ? 'Empresa #' . $empresaId : 'Empresa no disponible');
if ($empresaId > 0) {
    $empresaLabel .= $empresaNumeroControl !== ''
        ? sprintf(' (ID %d, NC %s)', $empresaId, $empresaNumeroControl)
        : sprintf(' (ID %d)', $empresaId);
}

$borradorRelativePath = $convenio !== null && isset($convenio['borrador_path']) && $convenio['borrador_path'] !== null
    ? trim((string) $convenio['borrador_path'])
    : '';
if ($borradorRelativePath !== '') {
    $normalizedPath = str_replace('\\', '/', ltrim($borradorRelativePath, '/\\'));
    $borradorUrl = '../../' . $normalizedPath;
    $borradorFileName = basename($normalizedPath);
} else {
    $borradorUrl = null;
    $borradorFileName = null;
}

$folioLabel = $convenio !== null
    ? convenioValueOrDefault($convenio['folio'] ?? null, 'Sin folio asignado')
    : 'Sin folio asignado';

$empresaLink = $empresaId > 0
    ? '../empresa/empresa_view.php?id=' . urlencode((string) $empresaId)
    : '#';
$convenioListLink = $empresaId > 0
    ? 'convenio_list.php?empresa=' . urlencode((string) $empresaId)
    : 'convenio_list.php';
$machoteLink = ($empresaId > 0 && $convenioId > 0)
    ? '../machote/revisar.php?id_empresa=' . urlencode((string) $empresaId) . '&convenio=' . urlencode((string) $convenioId)
    : '#';
$cancelLink = $convenioId > 0
    ? 'convenio_view.php?id=' . urlencode((string) $convenioId)
    : 'convenio_list.php';

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Convenio · Residencias Profesionales</title>

  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/convenios/convenio_edit.css" />
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

          <?php if ($controllerError !== null && $controller === null): ?>
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
                <label for="machote_version">Versión de machote</label>
                <input type="text" id="machote_version" name="machote_version" placeholder="Ej: v1.0"
                  value="<?php echo htmlspecialchars(convenioFormValue($formData, 'machote_version'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="version_actual">Versión actual del convenio</label>
                <input type="text" id="version_actual" name="version_actual" placeholder="Ej: v1.2"
                  value="<?php echo htmlspecialchars(convenioFormValue($formData, 'version_actual'), ENT_QUOTES, 'UTF-8'); ?>" />
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
