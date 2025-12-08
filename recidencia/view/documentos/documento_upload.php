<?php
declare(strict_types=1);

/** @var array{
 *     formData: array<string, string>,
 *     empresas: array<int, array<string, mixed>>,
 *     tiposGlobales: array<int, array<string, mixed>>,
 *     tiposPersonalizados: array<int, array<string, mixed>>,
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
$tiposGlobales = $handlerResult['tiposGlobales'];
$tiposPersonalizados = $handlerResult['tiposPersonalizados'];
$statusOptions = $handlerResult['statusOptions'];
$errors = $handlerResult['errors'];
$successMessage = $handlerResult['successMessage'];
$controllerError = $handlerResult['controllerError'];
$savedDocument = $handlerResult['savedDocument'];

$selectedEmpresaStatus = '';
if ($formData['empresa_id'] !== '') {
    foreach ($empresas as $empresa) {
        if ((string) ($empresa['id'] ?? '') === $formData['empresa_id']) {
            $selectedEmpresaStatus = (string) ($empresa['estatus'] ?? '');
            break;
        }
    }
}
$empresaIsCompletada = strcasecmp(trim($selectedEmpresaStatus), 'Completada') === 0;
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Subir Documento - Residencias Profesionales</title>

  <link rel="stylesheet" href="../../assets/css/modules/documentos/documentoupload.css" />
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div class="page-titles">
          <p class="eyebrow">Documentos</p>
          <h2>Subir Documento</h2>
          <p class="lead">Asocia el archivo a una empresa y define si es un requisito global o personalizado.</p>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>/</span>
            <a href="documento_list.php">Documentos</a>
            <span>/</span>
            <span>Subir</span>
          </nav>
        </div>
        <div class="actions">
          <a href="documento_list.php" class="btn secondary">Volver</a>
        </div>
      </header>

      <section class="card">
        <header>Datos del Documento</header>
        <div class="content">
          <?php if ($empresaIsCompletada): ?>
            <div class="alert error" role="alert">
              No se pueden subir documentos porque la empresa seleccionada estA? en estatus <strong>Completada</strong>.
            </div>
          <?php endif; ?>

          <p class="text-muted">
            Selecciona la empresa, define el origen (global o personalizado) y carga el archivo correspondiente.
          </p>

          <?php if ($controllerError !== null): ?>
            <div class="alert error" role="alert">
              <?php echo htmlspecialchars($controllerError, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <?php if ($successMessage !== null): ?>
            <div class="alert success" role="status">
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
            <div class="alert error" role="alert">
              <p style="margin:0 0 8px 0; font-weight:700;">Por favor corrige los siguientes errores:</p>
              <ul>
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
                    $empresaStatusLabel = isset($empresa['estatus']) ? (string) $empresa['estatus'] : '';
                    $isOptionCompletada = strcasecmp(trim($empresaStatusLabel), 'Completada') === 0;
                    ?>
                    <option value="<?php echo htmlspecialchars((string) $empresaId, ENT_QUOTES, 'UTF-8'); ?>"
                      <?php echo $formData['empresa_id'] === (string) $empresaId ? 'selected' : ''; ?>
                      data-estatus="<?php echo htmlspecialchars($empresaStatusLabel, ENT_QUOTES, 'UTF-8'); ?>">
                      <?php
                      $optionLabel = $empresaNombre !== '' ? $empresaNombre : 'Empresa #' . $empresaId;
                      if ($isOptionCompletada) {
                          $optionLabel .= ' (Completada)';
                      }
                      echo htmlspecialchars($optionLabel, ENT_QUOTES, 'UTF-8');
                      ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="field">
                <label for="tipo_origen" class="required">Origen del documento *</label>
                <select id="tipo_origen" name="tipo_origen" required>
                  <option value="global" <?php echo $formData['tipo_origen'] === 'global' ? 'selected' : ''; ?>>Global</option>
                  <option value="personalizado" <?php echo $formData['tipo_origen'] === 'personalizado' ? 'selected' : ''; ?>>Personalizado de la empresa</option>
                </select>
                <div class="help">
                  Selecciona "Global" para requisitos generales o "Personalizado" para documentos espec&iacute;ficos de la empresa.
                </div>
              </div>

              <div class="field" data-origen="global">
                <label for="tipo_global_id" class="required">Documento global *</label>
                <select id="tipo_global_id" name="tipo_global_id" <?php echo $formData['tipo_origen'] === 'global' ? 'required' : ''; ?>>
                  <option value="">-- Selecciona un documento global --</option>
                  <?php foreach ($tiposGlobales as $tipo): ?>
                    <?php
                    $tipoId = isset($tipo['id']) ? (int) $tipo['id'] : 0;
                    $tipoNombre = isset($tipo['nombre']) ? (string) $tipo['nombre'] : '';
                    ?>
                    <option value="<?php echo htmlspecialchars((string) $tipoId, ENT_QUOTES, 'UTF-8'); ?>"
                      <?php echo $formData['tipo_global_id'] === (string) $tipoId ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($tipoNombre !== '' ? $tipoNombre : 'Tipo global #' . $tipoId, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="field" data-origen="personalizado">
                <label for="tipo_personalizado_id" class="required">Documento personalizado *</label>
                <select id="tipo_personalizado_id" name="tipo_personalizado_id" <?php echo $formData['tipo_origen'] === 'personalizado' ? 'required' : ''; ?>>
                  <option value="">-- Selecciona un documento personalizado --</option>
                  <?php foreach ($tiposPersonalizados as $tipo): ?>
                    <?php
                    $tipoId = isset($tipo['id']) ? (int) $tipo['id'] : 0;
                    $tipoNombre = isset($tipo['nombre']) ? (string) $tipo['nombre'] : '';
                    $obligatorio = isset($tipo['obligatorio']) ? (bool) $tipo['obligatorio'] : false;
                    ?>
                    <option value="<?php echo htmlspecialchars((string) $tipoId, ENT_QUOTES, 'UTF-8'); ?>"
                      <?php echo $formData['tipo_personalizado_id'] === (string) $tipoId ? 'selected' : ''; ?>>
                      <?php
                      $label = $tipoNombre !== '' ? $tipoNombre : 'Tipo personalizado #' . $tipoId;
                      if ($obligatorio) {
                          $label .= ' (Obligatorio)';
                      }
                      echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
                      ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <?php if ($tiposPersonalizados === [] && $formData['empresa_id'] !== ''): ?>
                  <div class="help">La empresa seleccionada no tiene documentos personalizados registrados.</div>
                <?php else: ?>
                  <div class="help">Este listado se llena autom&aacute;ticamente con los documentos personalizados registrados para la empresa.</div>
                <?php endif; ?>
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
                  <input type="file" id="archivo" name="archivo" accept="application/pdf,image/*" required <?php echo $empresaIsCompletada ? 'disabled' : ''; ?> />
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

            <div class="actions form-actions">
              <a href="documento_list.php<?php echo $formData['empresa_id'] !== '' ? '?empresa=' . urlencode($formData['empresa_id']) : ''; ?>" class="btn secondary">Cancelar</a>
              <button type="submit" class="btn primary" <?php echo $empresaIsCompletada ? 'disabled' : ''; ?>>Guardar y subir</button>
            </div>
          </form>
        </div>
      </section>

      <?php if ($formData['empresa_id'] !== ''): ?>
        <section class="card">
          <header>Accesos r&aacute;pidos</header>
          <div class="content">
            <div class="quick-actions">
              <a class="btn" href="../empresa/empresa_view.php?id=<?php echo urlencode($formData['empresa_id']); ?>">Ver empresa</a>
              <a class="btn" href="documento_list.php?empresa=<?php echo urlencode($formData['empresa_id']); ?>">Ver documentos de esta empresa</a>
            </div>
          </div>
        </section>
      <?php endif; ?>
    </main>
  </div>

  <?php if ($successMessage !== null): ?>
    <script>
      window.addEventListener('DOMContentLoaded', function () {
        alert(<?php echo json_encode($successMessage, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>);
      });
    </script>
  <?php endif; ?>

  <script>
    (function () {
      var origenSelect = document.getElementById('tipo_origen');
      var globalField = document.querySelector('[data-origen="global"]');
      var personalField = document.querySelector('[data-origen="personalizado"]');
      var empresaSelect = document.getElementById('empresa_id');
      var globalSelect = document.getElementById('tipo_global_id');
      var personalSelect = document.getElementById('tipo_personalizado_id');

      function toggleFields() {
        var value = origenSelect ? origenSelect.value : 'global';
        if (globalField) {
          globalField.style.display = value === 'global' ? '' : 'none';
          if (globalSelect) {
            if (value === 'global') {
              globalSelect.setAttribute('required', 'required');
            } else {
              globalSelect.removeAttribute('required');
            }
          }
        }
        if (personalField) {
          personalField.style.display = value === 'personalizado' ? '' : 'none';
          if (personalSelect) {
            if (value === 'personalizado') {
              personalSelect.setAttribute('required', 'required');
            } else {
              personalSelect.removeAttribute('required');
            }
          }
        }
      }

      if (origenSelect) {
        origenSelect.addEventListener('change', toggleFields);
      }
      toggleFields();

      if (empresaSelect) {
        empresaSelect.addEventListener('change', function () {
          var value = empresaSelect.value;
          var params = new URLSearchParams();

          if (value) {
            params.set('empresa', value);
          }

          var origenValue = origenSelect ? origenSelect.value : '';
          if (origenValue) {
            params.set('origen', origenValue);
          }

          if (origenValue === 'global' && globalSelect && globalSelect.value) {
            params.set('tipo', globalSelect.value);
          }

          if (origenValue === 'personalizado' && personalSelect && personalSelect.value) {
            params.set('personalizado', personalSelect.value);
          }

          var query = params.toString();
          var base = window.location.pathname.replace(/\/+$/, '');
          window.location.href = query !== '' ? base + '?' + query : base;
        });
      }
    })();
  </script>
</body>

</html>





