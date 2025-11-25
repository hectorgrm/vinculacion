<?php
declare(strict_types=1);

/** @var array{
 *     formData: array<string, string>,
 *     estatusOptions: array<int, string>,
 *     errors: array<int, string>,
 *     successMessage: ?string,
 *     controllerError: ?string
 * } $handlerResult
 */
$handlerResult = require __DIR__ . '/../../handler/empresa/empresa_add_handler.php';

$formData = $handlerResult['formData'];
$estatusOptions = $handlerResult['estatusOptions'];
$errors = $handlerResult['errors'];
$successMessage = $handlerResult['successMessage'];
$controllerError = $handlerResult['controllerError'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrar Empresa · Residencias Profesionales</title>

  <!-- Estilos específicos para el formulario de empresa -->
  <link rel="stylesheet" href="../../assets/css/modules/empresa.css" />

</head>

<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <div>
          <h2>Registrar Nueva Empresa</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>›</span>
            <a href="empresa_list.php">Empresas</a>
            <span>›</span>
            <span>Registrar</span>
          </nav>
        </div>
        <a href="empresa_list.php" class="btn">⬅ Volver</a>
      </header>

      <section class="card">
        <header>🏢 Información de la Empresa</header>
        <div class="content">
          <p class="text-muted" style="margin-top:-6px">
            Completa el formulario para registrar una nueva empresa en el sistema de Residencias Profesionales.
          </p>

          <!-- FORMULARIO -->
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

          <?php $regimenFiscalOptions = empresaRegimenFiscalOptions(); ?>
          <form class="form" action="" method="post">
            <div class="grid">

              <!-- 🏢 Datos generales -->
              <h3 class="section-title">🏢 Información general</h3>
              <div class="field">
                <label for="numero_control" class="required">No. de control *</label>
                <input type="text" id="numero_control" name="numero_control" placeholder="Ej: EMP-0001" maxlength="20"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'numero_control'), ENT_QUOTES, 'UTF-8'); ?>" required />
              </div>
              <div class="field col-span-2">
                <label for="nombre" class="required">Nombre de la empresa *</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ej: Industrias Yakumo S.A. de C.V."
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'nombre'), ENT_QUOTES, 'UTF-8'); ?>"
                  required />
              </div>

              <div class="field">
                <label for="rfc" class="required">RFC *</label>
                <input type="text" id="rfc" name="rfc" placeholder="Ej: YAK930303CC3" maxlength="20"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'rfc'), ENT_QUOTES, 'UTF-8'); ?>" required />
              </div>

              <div class="field">
                <label for="representante">Representante legal</label>
                <input type="text" id="representante" name="representante" placeholder="Ej: José Manuel Velador"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'representante'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="cargo_representante">Cargo del representante</label>
                <input type="text" id="cargo_representante" name="cargo_representante"
                  placeholder="Ej: Director General"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'cargo_representante'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="sector">Sector o giro</label>
                <input type="text" id="sector" name="sector" placeholder="Ej: Educación / Social"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'sector'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="sitio_web">Sitio web</label>
                <input type="url" id="sitio_web" name="sitio_web" placeholder="https://www.empresa.mx"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'sitio_web'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <!-- 📞 Datos de contacto -->
              <h3 class="section-title">📞 Datos de contacto</h3>
              <div class="field">
                <label for="telefono" class="required">Teléfono *</label>
                <input type="text" id="telefono" name="telefono" placeholder="Ej: (33) 1234 5678"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'telefono'), ENT_QUOTES, 'UTF-8'); ?>" required />
              </div>

              <div class="field">
                <label for="contacto_nombre" class="required">Nombre del contacto *</label>
                <input type="text" id="contacto_nombre" name="contacto_nombre" placeholder="Ej: Luis Pérez"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'contacto_nombre'), ENT_QUOTES, 'UTF-8'); ?>" required />
              </div>

              <div class="field">
                <label for="contacto_email" class="required">Correo electrónico del contacto *</label>
                <input type="email" id="contacto_email" name="contacto_email" placeholder="Ej: contacto@empresa.com"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'contacto_email'), ENT_QUOTES, 'UTF-8'); ?>" required />
              </div>

              <!-- 📍 Ubicación -->
              <h3 class="section-title">📍 Ubicación</h3>
              <div class="field">
                <label for="estado" class="required">Estado / Entidad *</label>
                <input type="text" id="estado" name="estado" placeholder="Ej: Jalisco"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'estado'), ENT_QUOTES, 'UTF-8'); ?>" required />
              </div>

              <div class="field">
                <label for="municipio" class="required">Municipio / Alcaldía *</label>
                <input type="text" id="municipio" name="municipio" placeholder="Ej: Guadalajara"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'municipio'), ENT_QUOTES, 'UTF-8'); ?>" required />
              </div>

              <div class="field">
                <label for="cp" class="required">Código Postal *</label>
                <input type="text" id="cp" name="cp" placeholder="Ej: 44100"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'cp'), ENT_QUOTES, 'UTF-8'); ?>" required />
              </div>

              <div class="field col-span-2">
                <label for="direccion" class="required">Dirección (calle y número) *</label>
                <input type="text" id="direccion" name="direccion" placeholder="Ej: Calle Independencia 321"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'direccion'), ENT_QUOTES, 'UTF-8'); ?>" required />
              </div>

              <!-- ⚙️ Configuración -->
              <h3 class="section-title">⚙️ Configuración</h3>
              <div class="field">
                <label for="estatus" class="required">Estatus *</label>
                <select id="estatus" name="estatus" required>
                  <?php foreach ($estatusOptions as $option): ?>
                    <option value="<?php echo htmlspecialchars($option, ENT_QUOTES, 'UTF-8'); ?>" <?php echo empresaFormValue($formData, 'estatus') === $option ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($option, ENT_QUOTES, 'UTF-8'); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="field">
                <label for="regimen_fiscal" class="required">Régimen fiscal *</label>
                <select id="regimen_fiscal" name="regimen_fiscal" required>
                  <option value="">Selecciona tipo de empresa</option>
                  <?php foreach ($regimenFiscalOptions as $value => $label): ?>
                    <option value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>" <?php echo empresaFormValue($formData, 'regimen_fiscal') === $value ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="field col-span-2">
                <label for="notas">Notas / Observaciones</label>
                <textarea id="notas" name="notas" rows="3"
                  placeholder="Comentarios internos del área de vinculación..."><?php echo htmlspecialchars(empresaFormValue($formData, 'notas'), ENT_QUOTES, 'UTF-8'); ?></textarea>
              </div>

            </div>

            <div class="actions">
              <a href="empresa_list.php" class="btn">⬅ Cancelar</a>
              <button type="submit" class="btn primary">💾 Guardar Empresa</button>
            </div>
          </form>

        </div>
      </section>
    </main>
  </div>
  <?php if ($successMessage !== null): ?>
    <div id="empresa-success-toast"
      data-toast-message="<?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?>" hidden></div>

  <?php endif; ?>


  <!-- <script src="../../assets/js/validaciones/form-validation-es.js"></script> -->


</body>

</html>
