<?php
declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/empresadocumentotipo/empresa_documentotipo_functions_edit.php';

/** @var array{
 *     empresaId: ?int,
 *     documentoId: ?int,
 *     empresa: ?array<string, mixed>,
 *     documento: ?array<string, mixed>,
 *     formData: array<string, string>,
 *     errors: array<int, string>,
 *     successMessage: ?string,
 *     controllerError: ?string,
 *     inputError: ?string,
 *     notFoundMessage: ?string,
 *     documentoNotFoundMessage: ?string,
 *     isActivo: bool,
 *     supportsTipoEmpresa: bool,
 *     supportsActivo: bool
 * } $handlerResult
 */
$handlerResult = require __DIR__ . '/../../handler/empresadocumentotipo/empresa_documentotipo_edit_action.php';

$empresaId = $handlerResult['empresaId'];
$documentoId = $handlerResult['documentoId'];
$empresa = $handlerResult['empresa'];
$documento = $handlerResult['documento'];
$formData = $handlerResult['formData'];
$errors = $handlerResult['errors'];
$successMessage = $handlerResult['successMessage'];
$controllerError = $handlerResult['controllerError'];
$inputError = $handlerResult['inputError'];
$notFoundMessage = $handlerResult['notFoundMessage'];
$documentoNotFoundMessage = $handlerResult['documentoNotFoundMessage'];
$isActivo = $handlerResult['isActivo'];
$supportsTipoEmpresa = $handlerResult['supportsTipoEmpresa'];
$supportsActivo = $handlerResult['supportsActivo'];
$tipoEmpresaOptions = empresaDocumentoTipoEditTipoEmpresaOptions();

$empresaNombre = is_array($empresa) ? (string) ($empresa['nombre_label'] ?? ($empresa['nombre'] ?? '')) : '';
$empresaRfc = is_array($empresa) ? (string) ($empresa['rfc_label'] ?? ($empresa['rfc'] ?? '')) : '';
$empresaRegimen = is_array($empresa) ? (string) ($empresa['regimen_label'] ?? ($empresa['regimen_fiscal'] ?? '')) : '';

$nombreValue = empresaDocumentoTipoEditFormValue($formData, 'nombre');
$descripcionValue = empresaDocumentoTipoEditFormValue($formData, 'descripcion');
$obligatorioValue = empresaDocumentoTipoEditFormValue($formData, 'obligatorio') === '0' ? '0' : '1';
$tipoEmpresaValue = empresaDocumentoTipoEditFormValue($formData, 'tipo_empresa');

$listUrl = 'empresa_documentotipo_list.php';
if ($empresaId !== null) {
    $listUrl .= '?id_empresa=' . urlencode((string) $empresaId);
}

$formEnabled = $controllerError === null
    && $inputError === null
    && $notFoundMessage === null
    && $documentoNotFoundMessage === null;

$fieldDisabledAttribute = $isActivo ? '' : ' disabled';
$tipoEmpresaDisabledAttribute = (!$supportsTipoEmpresa || !$isActivo) ? ' disabled' : $fieldDisabledAttribute;

$reactivarUrl = null;
$desactivarUrl = null;

if ($supportsActivo && $documentoId !== null) {
    $baseParams = 'id=' . urlencode((string) $documentoId);
    if ($empresaId !== null) {
        $baseParams .= '&id_empresa=' . urlencode((string) $empresaId);
    }

    $reactivarUrl = 'reactivar.php?' . $baseParams;
    $desactivarUrl = 'empresa_documentotipo_delete.php?' . $baseParams;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar documento individual - Residencias Profesionales</title>

  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <link rel="stylesheet" href="../../assets/css/modules/documentotipo.css" />
  <link rel="stylesheet" href="../../assets/css/modules/empresa.css" />

  <style>
    .form-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
    }

    .form-grid .full {
      grid-column: 1 / -1;
    }

    .subtitle {
      font-size: 15px;
      color: #555;
      margin-top: 4px;
    }

    .summary {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 12px;
      margin-bottom: 16px;
      padding: 12px;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      background: #f7f8fa;
      font-size: 14px;
    }

    .actions {
      display: flex;
      flex-wrap: wrap;
      justify-content: flex-end;
      gap: 12px;
      margin-top: 20px;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 10px 16px;
      border-radius: 6px;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
      transition: background 0.2s;
    }

    .btn.primary {
      background: #007bff;
      color: #fff;
    }
    .btn.primary:hover { background: #0069d9; }

    .btn.secondary {
      background: #e0e0e0;
      color: #222;
    }
    .btn.secondary:hover { background: #d5d5d5; }

    .btn.desactivar {
      background: #f44336;
      color: #fff;
    }
    .btn.desactivar:hover { background: #d32f2f; }

    .btn.activar {
      background: #43a047;
      color: #fff;
    }
    .btn.activar:hover { background: #388e3c; }

    .badge.inactivo {
      background: #f8d7da;
      color: #721c24;
      padding: 3px 8px;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 600;
    }

    .field-note {
      font-size: 0.85rem;
      color: #757575;
      margin-top: 4px;
    }
  </style>
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>
            Editar documento individual
            <?php if ($documentoId !== null): ?>
              <span style="font-size:0.8em; font-weight:400;">#<?php echo htmlspecialchars((string) $documentoId, ENT_QUOTES, 'UTF-8'); ?></span>
            <?php endif; ?>
          </h2>
          <p class="subtitle">Actualiza la informacion del requisito personalizado asignado a esta empresa.</p>
        </div>
        <a href="<?php echo htmlspecialchars($listUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn secondary">&laquo; Volver</a>
      </header>

      <?php if ($controllerError !== null || $inputError !== null || $notFoundMessage !== null || $documentoNotFoundMessage !== null): ?>
        <section class="card">
          <header>Aviso</header>
          <div class="content">
            <?php if ($inputError !== null): ?>
              <div class="alert alert-danger">
                <?php echo htmlspecialchars($inputError, ENT_QUOTES, 'UTF-8'); ?>
              </div>
            <?php endif; ?>
            <?php if ($notFoundMessage !== null): ?>
              <div class="alert alert-danger">
                <?php echo htmlspecialchars($notFoundMessage, ENT_QUOTES, 'UTF-8'); ?>
              </div>
            <?php endif; ?>
            <?php if ($documentoNotFoundMessage !== null): ?>
              <div class="alert alert-danger">
                <?php echo htmlspecialchars($documentoNotFoundMessage, ENT_QUOTES, 'UTF-8'); ?>
              </div>
            <?php endif; ?>
            <?php if ($controllerError !== null): ?>
              <div class="alert alert-danger">
                <?php echo htmlspecialchars($controllerError, ENT_QUOTES, 'UTF-8'); ?>
              </div>
            <?php endif; ?>
            <p>Utiliza el boton volver para regresar al listado.</p>
          </div>
        </section>
      <?php endif; ?>

      <?php if ($formEnabled): ?>
        <section class="card">
          <header>Datos del documento</header>
          <div class="content">
            <?php if ($successMessage !== null): ?>
              <div class="alert alert-success" style="margin-bottom:16px;">
                <?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?>
              </div>
            <?php endif; ?>

            <?php if ($errors !== []): ?>
              <div class="alert alert-danger" style="margin-bottom:16px;">
                <p style="margin:0 0 8px 0; font-weight:600;">Corrige los siguientes puntos:</p>
                <ul style="margin:0; padding-left:18px;">
                  <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>

            <?php if (!$isActivo && $supportsActivo): ?>
              <div class="alert alert-warning" style="margin-bottom:16px;">
                Este documento se encuentra inactivo. Reactivalo para habilitar la edicion.
              </div>
            <?php endif; ?>

            <?php if ($empresa !== null): ?>
              <div class="summary">
                <div><strong>Empresa:</strong> <?php echo htmlspecialchars($empresaNombre !== '' ? $empresaNombre : 'Sin nombre', ENT_QUOTES, 'UTF-8'); ?></div>
                <div><strong>RFC:</strong> <?php echo htmlspecialchars($empresaRfc !== '' ? $empresaRfc : 'Sin RFC', ENT_QUOTES, 'UTF-8'); ?></div>
                <div><strong>Regimen fiscal:</strong> <?php echo htmlspecialchars($empresaRegimen !== '' ? $empresaRegimen : 'Sin datos', ENT_QUOTES, 'UTF-8'); ?></div>
              </div>
            <?php endif; ?>

            <form class="form" action="" method="post">
              <input type="hidden" name="empresa_id" value="<?php echo htmlspecialchars((string) ($empresaId ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
              <input type="hidden" name="documento_id" value="<?php echo htmlspecialchars((string) ($documentoId ?? ''), ENT_QUOTES, 'UTF-8'); ?>">

              <div class="form-grid">
                <div class="field full">
                  <label class="required" for="nombre">Nombre del documento *</label>
                  <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    maxlength="100"
                    value="<?php echo htmlspecialchars($nombreValue, ENT_QUOTES, 'UTF-8'); ?>"
                    required<?php echo $fieldDisabledAttribute; ?>
                  >
                </div>

                <div class="field full">
                  <label for="descripcion">Descripcion</label>
                  <textarea
                    id="descripcion"
                    name="descripcion"
                    rows="4"
                    placeholder="Describe el objetivo del documento o instrucciones para la empresa."<?php echo $fieldDisabledAttribute; ?>
                  ><?php echo htmlspecialchars($descripcionValue, ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>

                <div class="field">
                  <label class="required" for="obligatorio">Obligatorio *</label>
                  <select id="obligatorio" name="obligatorio" required<?php echo $fieldDisabledAttribute; ?>>
                    <option value="1" <?php echo $obligatorioValue === '1' ? 'selected' : ''; ?>>Si</option>
                    <option value="0" <?php echo $obligatorioValue === '0' ? 'selected' : ''; ?>>No</option>
                  </select>
                </div>

                <?php if ($supportsTipoEmpresa): ?>
                  <div class="field">
                    <label class="required" for="tipo_empresa">Tipo de empresa *</label>
                    <select id="tipo_empresa" name="tipo_empresa" required<?php echo $tipoEmpresaDisabledAttribute; ?>>
                      <?php foreach ($tipoEmpresaOptions as $value => $label): ?>
                        <option
                          value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>"
                          <?php echo $tipoEmpresaValue === $value ? 'selected' : ''; ?>
                        >
                          <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                <?php else: ?>
                  <input type="hidden" name="tipo_empresa" value="ambas">
                  <div class="field">
                    <label>Tipo de empresa</label>
                    <input type="text" value="No disponible para este documento" disabled>
                    <p class="field-note">
                      Agrega la columna <code>tipo_empresa</code> a la tabla <code>rp_documento_tipo_empresa</code> para habilitar esta opcion.
                    </p>
                  </div>
                <?php endif; ?>
              </div>

              <div class="actions">
                <a href="<?php echo htmlspecialchars($listUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn secondary">Cancelar</a>

                <?php if ($isActivo || !$supportsActivo): ?>
                  <button type="submit" class="btn primary">Guardar cambios</button>
                  <?php if ($supportsActivo && $desactivarUrl !== null): ?>
                    <a href="<?php echo htmlspecialchars($desactivarUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn desactivar">Desactivar</a>
                  <?php endif; ?>
                <?php else: ?>
                  <?php if ($reactivarUrl !== null): ?>
                    <a href="<?php echo htmlspecialchars($reactivarUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn activar">Reactivar</a>
                  <?php endif; ?>
                  <span class="badge inactivo">Documento inactivo</span>
                <?php endif; ?>
              </div>
            </form>
          </div>
        </section>
      <?php endif; ?>

      <section class="card" style="margin-top:20px;">
        <header>Informacion</header>
        <div class="content">
          <p>
            Los <strong>documentos individuales</strong> son requisitos creados exclusivamente para la empresa seleccionada.
          </p>
          <p>
            Si un documento se <strong>desactiva</strong>, deja de mostrarse a la empresa hasta que sea reactivado, pero su historial se conserva.
          </p>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
