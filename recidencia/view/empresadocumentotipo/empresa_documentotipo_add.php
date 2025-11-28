<?php
declare(strict_types=1);

/** @var array{
 *     empresaId: ?int,
 *     empresa: ?array<string, mixed>,
 *     formData: array<string, string>,
 *     errors: array<int, string>,
 *     successMessage: ?string,
 *     controllerError: ?string,
 *     inputError: ?string,
 *     notFoundMessage: ?string
 * } $handlerResult
 */
$handlerResult = require __DIR__ . '/../../handler/empresadocumentotipo/empresa_documentotipo_add_action.php';

$empresaId = $handlerResult['empresaId'];
$empresa = $handlerResult['empresa'];
$formData = $handlerResult['formData'];
$errors = $handlerResult['errors'];
$successMessage = $handlerResult['successMessage'];
$controllerError = $handlerResult['controllerError'];
$inputError = $handlerResult['inputError'];
$notFoundMessage = $handlerResult['notFoundMessage'];

$empresaNombre = is_array($empresa) ? (string) ($empresa['nombre_label'] ?? ($empresa['nombre'] ?? '')) : '';
$empresaRfc = is_array($empresa) ? (string) ($empresa['rfc_label'] ?? ($empresa['rfc'] ?? '')) : '';
$empresaRegimen = is_array($empresa) ? (string) ($empresa['regimen_label'] ?? ($empresa['regimen_fiscal'] ?? '')) : '';

$nombreValue = htmlspecialchars(empresaDocumentoTipoAddFormValue($formData, 'nombre'), ENT_QUOTES, 'UTF-8');
$descripcionValue = htmlspecialchars(empresaDocumentoTipoAddFormValue($formData, 'descripcion'), ENT_QUOTES, 'UTF-8');
$obligatorioValue = empresaDocumentoTipoAddFormValue($formData, 'obligatorio') === '0' ? '0' : '1';
$empresaIdValue = htmlspecialchars(empresaDocumentoTipoAddFormValue($formData, 'empresa_id'), ENT_QUOTES, 'UTF-8');

$listUrl = 'empresa_documentotipo_list.php';
if ($empresaId !== null) {
    $listUrl .= '?id_empresa=' . urlencode((string) $empresaId);
}

$formEnabled = $empresaId !== null && $empresa !== null && $inputError === null && $notFoundMessage === null && $controllerError === null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Nuevo documento individual - Residencias Profesionales</title>

  <link rel="stylesheet" href="../../assets/css/modules/documentotipo/empresadocumentotipoadd.css" />
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div class="page-titles">
          <p class="eyebrow">Documentos personalizados</p>
          <h2>Nuevo documento individual</h2>
          <p class="lead">Registra un requisito documental personalizado para esta empresa.</p>
        </div>
        <div class="actions">
          <a href="<?php echo htmlspecialchars($listUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn secondary">&laquo; Volver</a>
        </div>
      </header>

      <section class="card">
        <header>Datos del documento</header>
        <div class="content">
          <?php if ($inputError !== null): ?>
            <div class="alert error" role="alert">
              <?php echo htmlspecialchars($inputError, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <?php if ($notFoundMessage !== null): ?>
            <div class="alert error" role="alert">
              <?php echo htmlspecialchars($notFoundMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <?php if ($controllerError !== null): ?>
            <div class="alert error" role="alert">
              <?php echo htmlspecialchars($controllerError, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <?php if ($successMessage !== null): ?>
            <div class="alert success" role="status">
              <?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <?php if ($errors !== []): ?>
            <div class="alert error" role="alert">
              <p style="margin:0 0 8px 0; font-weight:700;">Corrige los siguientes puntos:</p>
              <ul style="margin:0 0 0 18px; padding:0;">
                <?php foreach ($errors as $error): ?>
                  <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <?php if ($empresa !== null): ?>
            <div class="summary">
              <div><strong>Empresa:</strong> <?php echo htmlspecialchars($empresaNombre !== '' ? $empresaNombre : 'Sin nombre', ENT_QUOTES, 'UTF-8'); ?></div>
              <div><strong>RFC:</strong> <?php echo htmlspecialchars($empresaRfc !== '' ? $empresaRfc : 'Sin RFC', ENT_QUOTES, 'UTF-8'); ?></div>
              <div><strong>R&eacute;gimen fiscal:</strong> <?php echo htmlspecialchars($empresaRegimen !== '' ? $empresaRegimen : 'Sin datos', ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
          <?php endif; ?>

          <?php if ($formEnabled): ?>
            <form class="form" action="" method="post">
              <input type="hidden" name="empresa_id" value="<?php echo $empresaIdValue; ?>">

              <div class="form-grid">
                <div class="field full">
                  <label class="required" for="nombre">Nombre del documento *</label>
                  <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    maxlength="100"
                    placeholder="Ejemplo: Certificado de Seguridad"
                    value="<?php echo $nombreValue; ?>"
                    required
                  >
                </div>

                <div class="field full">
                  <label for="descripcion">Descripci&oacute;n</label>
                  <textarea
                    id="descripcion"
                    name="descripcion"
                    rows="3"
                    placeholder="Describe el objetivo del documento o instrucciones para la empresa."
                  ><?php echo $descripcionValue; ?></textarea>
                </div>

                <div class="field">
                  <label class="required" for="obligatorio">Obligatorio *</label>
                  <select id="obligatorio" name="obligatorio" required>
                    <option value="1" <?php echo $obligatorioValue === '1' ? 'selected' : ''; ?>>S&iacute;</option>
                    <option value="0" <?php echo $obligatorioValue === '0' ? 'selected' : ''; ?>>No</option>
                  </select>
                </div>
              </div>

              <div class="actions form-actions">
                <a href="<?php echo htmlspecialchars($listUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn secondary">Cancelar</a>
                <button type="submit" class="btn primary">Guardar</button>
              </div>
            </form>
          <?php endif; ?>

          <?php if (!$formEnabled): ?>
            <p class="text-muted">No es posible registrar documentos individuales en este momento.</p>
          <?php endif; ?>
        </div>
      </section>

      <section class="card">
        <header>Informaci&oacute;n</header>
        <div class="content info-panel">
          <p>
            Los <strong>documentos individuales</strong> son requisitos creados exclusivamente para la empresa seleccionada.
            No afectan a otras empresas dentro del sistema.
          </p>
          <p>
            Una vez registrado, el documento aparecer&aacute; en el listado de la empresa y podr&aacute; recibir archivos,
            revisiones y estatus igual que los documentos globales.
          </p>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
