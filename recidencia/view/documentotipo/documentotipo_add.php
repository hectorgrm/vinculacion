<?php
declare(strict_types=1);

/** @var array{
 *     formData: array<string, string>,
 *     tipoEmpresaOptions: array<string, string>,
 *     errors: array<int, string>,
 *     successMessage: ?string,
 *     controllerError: ?string
 * } $handlerResult
 */
$handlerResult = require __DIR__ . '/../../handler/documentotipo/documentotipo_add_handler.php';

$formData = $handlerResult['formData'];
$tipoEmpresaOptions = $handlerResult['tipoEmpresaOptions'];
$errors = $handlerResult['errors'];
$successMessage = $handlerResult['successMessage'];
$controllerError = $handlerResult['controllerError'];

$nombreValue = htmlspecialchars($formData['nombre'] ?? '', ENT_QUOTES, 'UTF-8');
$descripcionValue = htmlspecialchars($formData['descripcion'] ?? '', ENT_QUOTES, 'UTF-8');
$obligatorioValue = $formData['obligatorio'] ?? '1';
$tipoEmpresaValue = $formData['tipo_empresa'] ?? 'ambas';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nuevo Tipo de Documento - Residencias</title>

  <link rel="stylesheet" href="../../assets/stylesrecidencia.css"/>
  <link rel="stylesheet" href="../../assets/css/modules/documentotipo/documentotipoadd.css" />
</head>
<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>Nuevo Tipo de Documento</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a><span>&rsaquo;</span>
            <a href="documentotipo_list.php">Tipos de Documento</a><span>&rsaquo;</span>
            <span>Nuevo</span>
          </nav>
        </div>
        <a href="documentotipo_list.php" class="btn">&laquo; Volver</a>
      </header>

      <section class="card">
        <header>Datos del documento</header>
        <div class="content">
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
              <p style="margin:0 0 8px 0; font-weight:600;">Corrige los siguientes errores:</p>
              <ul style="margin:0 0 0 18px; padding:0;">
                <?php foreach ($errors as $error): ?>
                  <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <form class="form" method="post" action="">
            <div class="grid">
              <div class="field">
                <label class="required" for="nombre">Nombre *</label>
                <input
                  id="nombre"
                  name="nombre"
                  type="text"
                  maxlength="100"
                  placeholder="Ej: Constancia de situacion fiscal (SAT)"
                  value="<?php echo $nombreValue; ?>"
                  required
                >
              </div>

              <div class="field col-span-2">
                <label for="descripcion">Descripcion</label>
                <textarea
                  id="descripcion"
                  name="descripcion"
                  rows="3"
                  placeholder="Describe el proposito del documento."
                ><?php echo $descripcionValue; ?></textarea>
              </div>

              <div class="field">
                <label class="required" for="obligatorio">Obligatorio *</label>
                <select id="obligatorio" name="obligatorio" required>
                  <option value="1" <?php echo $obligatorioValue === '1' ? 'selected' : ''; ?>>Si</option>
                  <option value="0" <?php echo $obligatorioValue === '0' ? 'selected' : ''; ?>>No</option>
                </select>
              </div>

              <div class="field">
                <label class="required" for="tipo_empresa">Tipo de empresa *</label>
                <select id="tipo_empresa" name="tipo_empresa" required>
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
            </div>

            <div class="actions">
              <a class="btn" href="documentotipo_list.php">Cancelar</a>
              <button class="btn primary" type="submit">Guardar</button>
            </div>
          </form>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
