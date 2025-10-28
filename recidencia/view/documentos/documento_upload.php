<?php
declare(strict_types=1);

/** @var array{
 *     formData: array<string, string>,
 *     empresas: array<int, array<string, mixed>>,
 *     convenios: array<int, array<string, mixed>>,
 *     tipos: array<int, array<string, mixed>>,
 *     statusOptions: array<string, string>,
 *     errors: array<int, string>,
 *     successMessage: ?string,
 *     controllerError: ?string,
 *     savedDocument: ?array<string, mixed>
 * } $handlerResult
 */
$handlerResult = require __DIR__ . '/../../handler/documento/documento_upload_handler.php';

$formData = $handlerResult['formData'];
$empresas = $handlerResult['empresas'];
$convenios = $handlerResult['convenios'];
$tipos = $handlerResult['tipos'];
$statusOptions = $handlerResult['statusOptions'];
$errors = $handlerResult['errors'];
$successMessage = $handlerResult['successMessage'];
$controllerError = $handlerResult['controllerError'];
$savedDocument = $handlerResult['savedDocument'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Subir Documento - Residencias Profesionales</title>

  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/documentos/documento_upload.css" />


</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>Subir Documento</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>/</span>
            <a href="documento_list.php">Documentos</a>
            <span>/</span>
            <span>Subir</span>
          </nav>
        </div>
        <a href="documento_list.php" class="btn">Volver</a>
      </header>

      <section class="card">
        <header>Datos del Documento</header>
        <div class="content">
          <p class="text-muted" style="margin-top:-6px">
            Asocia el documento a una empresa y, si aplica, a un convenio. Define su tipo, estatus y agrega observaciones.
          </p>

          <?php if ($controllerError !== null): ?>
            <div class="alert alert-danger" style="margin-bottom:16px;">
              <?php echo htmlspecialchars($controllerError, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <?php if ($successMessage !== null): ?>
            <div class="alert alert-success" style="margin-bottom:16px;">
              <p style="margin:0 0 8px 0;"><?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?></p>
              <?php if (is_array($savedDocument) && isset($savedDocument['ruta'])): ?>
                <?php
                $savedUrl = '../../' . ltrim(str_replace('\\', '/', (string) $savedDocument['ruta']), '/');
                $savedOriginal = isset($savedDocument['originalName']) ? (string) $savedDocument['originalName'] : '';
                ?>
                <a class="btn small" href="<?php echo htmlspecialchars($savedUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">
                  Ver archivo guardado
                </a>
                <?php if ($savedOriginal !== ''): ?>
                  <span class="text-muted" style="margin-left:8px;">
                    Archivo: <?php echo htmlspecialchars($savedOriginal, ENT_QUOTES, 'UTF-8'); ?>
                  </span>
                <?php endif; ?>
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

          <form class="form" action="" method="post" enctype="multipart/form-data">
            <div class="grid">
              <div class="field">
                <label for="empresa_id" class="required">Empresa *</label>
                <select id="empresa_id" name="empresa_id" required>
                  <option value="">-- Selecciona una empresa --</option>
                  <?php foreach ($empresas as $empresa): ?>
                    <?php
                    $empresaId = isset($empresa['id']) ? (int) $empresa['id'] : 0;
                    $empresaNombre = isset($empresa['nombre']) ? (string) $empresa['nombre'] : '';
                    ?>
                    <option value="<?php echo htmlspecialchars((string) $empresaId, ENT_QUOTES, 'UTF-8'); ?>"
                      <?php echo $formData['empresa_id'] === (string) $empresaId ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($empresaNombre !== '' ? $empresaNombre : 'Empresa #' . $empresaId, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="field">
                <label for="convenio_id">Convenio (opcional)</label>
                <select id="convenio_id" name="convenio_id">
                  <option value="">-- Sin convenio asociado --</option>
                  <?php foreach ($convenios as $convenio): ?>
                    <?php
                    $convenioId = isset($convenio['id']) ? (int) $convenio['id'] : 0;
                    $folio = isset($convenio['folio']) ? trim((string) $convenio['folio']) : '';
                    $version = isset($convenio['version_actual']) ? trim((string) $convenio['version_actual']) : '';
                    $estatusConvenio = isset($convenio['estatus']) ? trim((string) $convenio['estatus']) : '';
                    $labelParts = [];

                    if ($folio !== '') {
                      $labelParts[] = '#' . $folio;
                    } else {
                      $labelParts[] = 'Convenio #' . $convenioId;
                    }

                    if ($version !== '') {
                      $labelParts[] = 'v' . $version;
                    }

                    if ($estatusConvenio !== '') {
                      $labelParts[] = $estatusConvenio;
                    }

                    $convenioLabel = implode(' - ', $labelParts);
                    ?>
                    <option value="<?php echo htmlspecialchars((string) $convenioId, ENT_QUOTES, 'UTF-8'); ?>"
                      <?php echo $formData['convenio_id'] === (string) $convenioId ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($convenioLabel, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <div class="help">
                  Selecciona primero la empresa para ver sus convenios disponibles.
                </div>
              </div>

              <div class="field">
                <label for="tipo_id" class="required">Tipo de documento *</label>
                <select id="tipo_id" name="tipo_id" required>
                  <option value="">-- Selecciona un tipo --</option>
                  <?php foreach ($tipos as $tipo): ?>
                    <?php
                    $tipoId = isset($tipo['id']) ? (int) $tipo['id'] : 0;
                    $tipoNombre = isset($tipo['nombre']) ? (string) $tipo['nombre'] : '';
                    ?>
                    <option value="<?php echo htmlspecialchars((string) $tipoId, ENT_QUOTES, 'UTF-8'); ?>"
                      <?php echo $formData['tipo_id'] === (string) $tipoId ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($tipoNombre !== '' ? $tipoNombre : 'Tipo #' . $tipoId, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="field">
                <label for="estatus" class="required">Estatus *</label>
                <select id="estatus" name="estatus" required>
                  <?php foreach ($statusOptions as $value => $label): ?>
                    <option value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>"
                      <?php echo $formData['estatus'] === $value ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="field col-span-2">
                <label for="archivo" class="required">Archivo *</label>
                <div class="dropzone">
                  <input type="file" id="archivo" name="archivo" accept="application/pdf,image/*" required />
                  <div>Arrastra tu archivo aqu&iacute; o haz clic para seleccionar</div>
                  <div class="file-hint">Formatos permitidos: PDF o imagen (JPG/PNG). Tama&ntilde;o m&aacute;ximo 10 MB.</div>
                </div>
              </div>

              <div class="field">
                <label for="fecha_doc">Fecha del documento (opcional)</label>
                <input type="date" id="fecha_doc" name="fecha_doc" value="<?php echo htmlspecialchars($formData['fecha_doc'], ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field col-span-2">
                <label for="observacion">Observaciones</label>
                <textarea id="observacion" name="observacion" rows="4" placeholder="Comentarios o notas internas..."><?php echo htmlspecialchars($formData['observacion'], ENT_QUOTES, 'UTF-8'); ?></textarea>
              </div>
            </div>

            <div class="actions">
              <a href="documento_list.php<?php echo $formData['empresa_id'] !== '' ? '?empresa=' . urlencode($formData['empresa_id']) : ''; ?>" class="btn">Cancelar</a>
              <button type="submit" class="btn primary">Guardar y subir</button>
            </div>
          </form>
        </div>
      </section>

      <?php if ($formData['empresa_id'] !== ''): ?>
        <section class="card">
          <header>Accesos r&aacute;pidos</header>
          <div class="content actions" style="justify-content:flex-start;">
            <a class="btn" href="../empresa/empresa_view.php?id=<?php echo urlencode($formData['empresa_id']); ?>">Ver empresa</a>
            <?php if ($formData['convenio_id'] !== ''): ?>
              <a class="btn" href="../convenio/convenio_view.php?id=<?php echo urlencode($formData['convenio_id']); ?>">Ver convenio</a>
            <?php endif; ?>
            <a class="btn" href="documento_list.php?empresa=<?php echo urlencode($formData['empresa_id']); ?>">Ver documentos de esta empresa</a>
          </div>
        </section>
      <?php endif; ?>
    </main>
  </div>
</body>

</html>
