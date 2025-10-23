<?php
declare(strict_types=1);

require_once __DIR__ . '/../../controller/ConvenioController.php';
require_once __DIR__ . '/../../common/functions/conveniofunction.php';

$formData = convenioFormDefaults();
$estatusOptions = convenioStatusOptions();
$errors = [];
$successMessage = null;

$controllerData = convenioResolveControllerData();
$controller = $controllerData['controller'];
$controllerError = $controllerData['error'];
$empresaOptions = $controllerData['empresaOptions'];

if ($controller !== null && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $handleResult = convenioHandleAddRequest(
        $controller,
        $_POST,
        $_FILES,
        convenioUploadsAbsoluteDir(),
        convenioUploadsRelativeDir()
    );

    $formData = $handleResult['formData'];
    $errors = $handleResult['errors'];
    $successMessage = $handleResult['successMessage'];
} elseif ($controller === null && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors[] = $controllerError ?? 'No se pudo procesar la solicitud.';
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrar Convenio - Residencias Profesionales</title>

  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/convenios/convenioadd.css" />
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>Registrar Nuevo Convenio</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>&rsaquo;</span>
            <a href="convenio_list.php">Convenios</a>
            <span>&rsaquo;</span>
            <span>Nuevo</span>
          </nav>
        </div>
        <a href="convenio_list.php" class="btn">&larr; Volver</a>
      </header>

      <section class="card">
        <header>Formulario de Alta de Convenio</header>
        <div class="content">
          <p class="text-muted" style="margin-top:-6px">
            Registra un convenio vinculado a una empresa. Puedes adjuntar el archivo y definir su vigencia.
          </p>

          <?php if ($controllerError !== null): ?>
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

          <form class="form" method="POST" action="" enctype="multipart/form-data">
            <div class="grid">

              <!-- Empresa -->
              <div class="field col-span-2">
                <label for="empresa_id" class="required">Empresa *</label>
                <select name="empresa_id" id="empresa_id" required <?php echo $controller === null ? 'disabled' : ''; ?>>
                  <option value="">-- Selecciona una empresa --</option>
                  <?php foreach ($empresaOptions as $empresa): ?>
                  <?php
                    $empresaId = isset($empresa['id']) ? (string) $empresa['id'] : '';
                    $empresaNombre = isset($empresa['nombre']) ? (string) $empresa['nombre'] : '';
                    $numeroControl = isset($empresa['numero_control']) ? trim((string) $empresa['numero_control']) : '';
                    $label = $empresaNombre;

                    if ($numeroControl !== '') {
                        $label .= ' (NC: ' . $numeroControl . ')';
                    }
                  ?>
                  <option value="<?php echo htmlspecialchars($empresaId, ENT_QUOTES, 'UTF-8'); ?>"
                    <?php echo convenioFormValue($formData, 'empresa_id') === $empresaId ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Folio -->
              <div class="field">
                <label for="folio">Folio del convenio</label>
                <input type="text" name="folio" id="folio" placeholder="Ej: CBR-2025-01"
                  value="<?php echo htmlspecialchars(convenioFormValue($formData, 'folio'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <!-- Estatus -->
              <div class="field">
                <label for="estatus" class="required">Estatus del convenio *</label>
                <select name="estatus" id="estatus" required>
                  <?php foreach ($estatusOptions as $option): ?>
                  <option value="<?php echo htmlspecialchars($option, ENT_QUOTES, 'UTF-8'); ?>"
                    <?php echo convenioFormValue($formData, 'estatus') === $option ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($option, ENT_QUOTES, 'UTF-8'); ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Machote versión -->
              <div class="field">
                <label for="machote_version">Versión de machote</label>
                <input type="text" name="machote_version" id="machote_version" placeholder="Ej: v1.0"
                  value="<?php echo htmlspecialchars(convenioFormValue($formData, 'machote_version'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <!-- Versión actual -->
              <div class="field">
                <label for="version_actual">Versión actual del convenio</label>
                <input type="text" name="version_actual" id="version_actual" placeholder="Ej: v1.2"
                  value="<?php echo htmlspecialchars(convenioFormValue($formData, 'version_actual'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <!-- Fechas -->
              <div class="field">
                <label for="fecha_inicio">Fecha de inicio</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio"
                  value="<?php echo htmlspecialchars(convenioFormValue($formData, 'fecha_inicio'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="fecha_fin">Fecha de término</label>
                <input type="date" name="fecha_fin" id="fecha_fin"
                  value="<?php echo htmlspecialchars(convenioFormValue($formData, 'fecha_fin'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <!-- Archivo -->
              <div class="field col-span-2">
                <label for="borrador_path">Archivo del convenio (PDF)</label>
                <input type="file" name="borrador_path" id="borrador_path" accept="application/pdf" />
              </div>

              <!-- Observaciones -->
              <div class="field col-span-2">
                <label for="observaciones">Notas / Observaciones</label>
                <textarea name="observaciones" id="observaciones" rows="4"
                  placeholder="Comentarios internos del área de vinculación..."><?php echo htmlspecialchars(convenioFormValue($formData, 'observaciones'), ENT_QUOTES, 'UTF-8'); ?></textarea>
              </div>
            </div>

            <div class="actions">
              <a href="convenio_list.php" class="btn">Cancelar</a>
              <button type="submit" class="btn primary" <?php echo $controller === null ? 'disabled' : ''; ?>>Guardar
                Convenio</button>
            </div>
          </form>
        </div>
      </section>
    </main>
  </div>
</body>

</html>
