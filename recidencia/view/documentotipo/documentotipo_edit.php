<?php
declare(strict_types=1);

/** @var array{
 *     documentoTipoId: ?int,
 *     formData: array<string, string>,
 *     tipoEmpresaOptions: array<string, string>,
 *     errors: array<int, string>,
 *     successMessage: ?string,
 *     controllerError: ?string,
 *     loadError: ?string
 * } $handlerResult
 */
$handlerResult = require __DIR__ . '/../../handler/documentotipo/documentotipo_edit_handler.php';

$documentoTipoId = $handlerResult['documentoTipoId'];
$formData = $handlerResult['formData'];
$tipoEmpresaOptions = $handlerResult['tipoEmpresaOptions'];
$errors = $handlerResult['errors'];
$successMessage = $handlerResult['successMessage'];
$controllerError = $handlerResult['controllerError'];
$loadError = $handlerResult['loadError'];

$nombreValue = documentoTipoEditFormValue($formData, 'nombre');
$descripcionValue = documentoTipoEditFormValue($formData, 'descripcion');
$obligatorioValue = documentoTipoEditFormValue($formData, 'obligatorio');
$tipoEmpresaValue = documentoTipoEditFormValue($formData, 'tipo_empresa');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Tipo de Documento - Residencias</title>

  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/documentotipo/documentotipo.css" />
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>
            Editar Tipo de Documento
            <?php if ($documentoTipoId !== null): ?>
              <span style="font-size:0.8em; font-weight:400;">#<?php echo htmlspecialchars((string) $documentoTipoId, ENT_QUOTES, 'UTF-8'); ?></span>
            <?php endif; ?>
          </h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a><span>&rsaquo;</span>
            <a href="documentotipo_list.php">Tipos de Documento</a><span>&rsaquo;</span>
            <span>Editar</span>
          </nav>
        </div>
        <a href="documentotipo_list.php" class="btn secondary">&laquo; Volver</a>
      </header>

      <?php if ($controllerError !== null || $loadError !== null): ?>
        <section class="card">
          <header>Aviso</header>
          <div class="content">
            <?php if ($controllerError !== null): ?>
              <div class="alert alert-danger">
                <?php echo htmlspecialchars($controllerError, ENT_QUOTES, 'UTF-8'); ?>
              </div>
            <?php endif; ?>
            <?php if ($loadError !== null): ?>
              <div class="alert alert-danger">
                <?php echo htmlspecialchars($loadError, ENT_QUOTES, 'UTF-8'); ?>
              </div>
            <?php endif; ?>
            <p>Utiliza el bot&oacute;n volver para regresar al listado.</p>
          </div>
        </section>
      <?php else: ?>
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
                <p style="margin:0 0 8px 0; font-weight:600;">Corrige los siguientes errores:</p>
                <ul style="margin:0; padding-left:18px;">
                  <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>

            <form class="form" method="post" action="">
              <input type="hidden" name="documento_tipo_id" value="<?php echo htmlspecialchars((string) $documentoTipoId, ENT_QUOTES, 'UTF-8'); ?>" />

              <div class="form-grid">
                <div class="field">
                  <label class="required" for="nombre">Nombre *</label>
                  <input
                    id="nombre"
                    name="nombre"
                    type="text"
                    maxlength="100"
                    placeholder="Ej: Constancia SAT"
                    value="<?php echo htmlspecialchars($nombreValue, ENT_QUOTES, 'UTF-8'); ?>"
                    required
                  />
                </div>

                <div class="field">
                  <label class="required" for="tipo_empresa">Tipo de empresa *</label>
                  <select id="tipo_empresa" name="tipo_empresa" required>
                    <?php foreach ($tipoEmpresaOptions as $value => $label): ?>
                      <option
                        value="<?php echo htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); ?>"
                        <?php echo $tipoEmpresaValue === $value ? 'selected' : ''; ?>
                      >
                        <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="field">
                  <label class="required" for="obligatorio">Es obligatorio *</label>
                  <select id="obligatorio" name="obligatorio" required>
                    <option value="1" <?php echo $obligatorioValue === '1' ? 'selected' : ''; ?>>Si</option>
                    <option value="0" <?php echo $obligatorioValue === '0' ? 'selected' : ''; ?>>No</option>
                  </select>
                </div>

                <div class="field full">
                  <label for="descripcion">Descripcion</label>
                  <textarea
                    id="descripcion"
                    name="descripcion"
                    rows="4"
                    placeholder="Describe el documento y en que casos se solicita."
                  ><?php echo htmlspecialchars($descripcionValue, ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>
              </div>

              <div class="actions">
                <a href="documentotipo_list.php" class="btn secondary">Cancelar</a>
                <button type="submit" class="btn primary">Guardar cambios</button>
              </div>
            </form>
          </div>
        </section>

        <section class="card" style="margin-top:20px;">
          <header>Informacion</header>
          <div class="content">
            <p>Los tipos de documento definen los requisitos oficiales que veran las empresas al cargar su documentacion.</p>
            <p>Cuando actualices un tipo de documento, los cambios se reflejan de inmediato en los formularios de las empresas.</p>
          </div>
        </section>
      <?php endif; ?>
    </main>
  </div>
</body>
</html>

