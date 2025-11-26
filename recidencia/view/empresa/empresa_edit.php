<?php

declare(strict_types=1);

/** @var array{
 *     empresaId: ?int,
 *     formData: array<string, string>,
 *     estatusOptions: array<int, string>,
 *     errors: array<int, string>,
 *     successMessage: ?string,
 *     controllerError: ?string,
 *     loadError: ?string
 * } $handlerResult
 */
$handlerResult = require __DIR__ . '/../../handler/empresa/empresa_edit_handler.php';

$empresaId = $handlerResult['empresaId'];
$formData = $handlerResult['formData'];
$estatusOptions = $handlerResult['estatusOptions'];
$errors = $handlerResult['errors'];
$successMessage = $handlerResult['successMessage'];
$controllerError = $handlerResult['controllerError'];
$loadError = $handlerResult['loadError'];

$empresaNombre = empresaFormValue($formData, 'nombre');
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>✏️ Editar Empresa · Residencias Profesionales</title>

  <link rel="stylesheet" href="../../assets/css/modules/empresa/empresaedit.css" />
</head>

<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <div>
          <h2>✏️ Editar Empresa</h2>
          <p class="subtitle">Actualiza la información institucional y de contacto</p>
        </div>
        <div class="top-actions">
          <a href="empresa_list.php" class="btn secondary">⬅ Volver</a>
          <?php if ($empresaId !== null && $loadError === null && $controllerError === null) : ?>
            <a href="empresa_view.php?id=<?php echo urlencode((string) $empresaId); ?>" class="btn">👁️ Ver</a>
          <?php endif; ?>
        </div>
      </header>

      <?php if ($controllerError !== null || $loadError !== null) : ?>
        <section class="card">
          <header>⚠️ Aviso</header>
          <div class="content">
            <?php if ($controllerError !== null) : ?>
              <div class="alert error">
                <?php echo htmlspecialchars($controllerError, ENT_QUOTES, 'UTF-8'); ?>
              </div>
            <?php endif; ?>
            <?php if ($loadError !== null) : ?>
              <div class="alert error">
                <?php echo htmlspecialchars($loadError, ENT_QUOTES, 'UTF-8'); ?>
              </div>
            <?php endif; ?>
            <p>Utiliza el enlace anterior para volver al listado de empresas.</p>
          </div>
        </section>
      <?php else : ?>

        <!-- Aviso contextual -->
        <section class="card">
          <header>📌 Contexto</header>
          <div class="content">
            <div class="alert info">
              Estás editando la empresa <strong><?php echo htmlspecialchars($empresaNombre, ENT_QUOTES, 'UTF-8'); ?></strong>
              (ID <strong>#<?php echo htmlspecialchars((string) $empresaId, ENT_QUOTES, 'UTF-8'); ?></strong>).
            </div>
          </div>
        </section>

        <!-- Formulario principal -->
        <form class="form-grid" method="post" action="">
          <input type="hidden" name="empresa_id" value="<?php echo htmlspecialchars((string) $empresaId, ENT_QUOTES, 'UTF-8'); ?>" />

          <?php if ($successMessage !== null) : ?>
            <div class="alert success" role="alert">
              <?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <?php if ($errors !== []) : ?>
            <div class="alert error" role="alert">
              <p style="margin:0 0 8px 0; font-weight:600;">Por favor corrige los siguientes errores:</p>
              <ul style="margin:0; padding-left:18px;">
                <?php foreach ($errors as $error) : ?>
                  <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <!-- 📄 Datos generales -->
          <section class="card">
            <header>🏢 Datos Generales</header>
            <div class="content grid">
              <div class="field">
                <label for="numero_control">No. de control</label>
                <input id="numero_control" name="numero_control" type="text" maxlength="20" placeholder="Ej: EMP-0001"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'numero_control'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>
              <div class="field">
                <label for="nombre" class="required">Nombre de la empresa *</label>
                <input id="nombre" name="nombre" type="text" required placeholder="Ej: Casa del Barrio"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'nombre'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="rfc">RFC</label>
                <input id="rfc" name="rfc" type="text" maxlength="20" placeholder="Ej: CDB810101AA1"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'rfc'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="representante">Representante legal</label>
                <input id="representante" name="representante" type="text" placeholder="Ej: José Manuel Velador"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'representante'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="cargo_representante">Cargo del representante</label>
                <input id="cargo_representante" name="cargo_representante" type="text" placeholder="Ej: Director General"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'cargo_representante'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="sector">Sector / Giro</label>
                <input id="sector" name="sector" type="text" placeholder="Ej: Educación / Social"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'sector'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="sitio_web">Sitio web</label>
                <input id="sitio_web" name="sitio_web" type="url" placeholder="https://www.empresa.mx"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'sitio_web'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>
            </div>
          </section>

          <!-- 📬 Contacto y Dirección -->
          <section class="card">
            <header>📬 Contacto y Dirección</header>
            <div class="content grid">
              <div class="field">
                <label for="contacto_nombre">Nombre de contacto</label>
                <input id="contacto_nombre" name="contacto_nombre" type="text" placeholder="Ej: Responsable RH"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'contacto_nombre'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="contacto_email">Correo electrónico</label>
                <input id="contacto_email" name="contacto_email" type="email" placeholder="contacto@empresa.mx"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'contacto_email'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="telefono">Teléfono</label>
                <input id="telefono" name="telefono" type="tel" placeholder="(33) 1234 5678"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'telefono'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="estado">Estado</label>
                <input id="estado" name="estado" type="text" placeholder="Ej: Jalisco"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'estado'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="municipio">Municipio / Alcaldía</label>
                <input id="municipio" name="municipio" type="text" placeholder="Ej: Guadalajara"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'municipio'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="cp">Código Postal</label>
                <input id="cp" name="cp" type="text" placeholder="Ej: 44100"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'cp'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field col-span-2">
                <label for="direccion">Dirección (calle y número)</label>
                <input id="direccion" name="direccion" type="text" placeholder="Ej: Calle Independencia 321"
                  value="<?php echo htmlspecialchars(empresaFormValue($formData, 'direccion'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>
            </div>
          </section>

          <!-- ⚙️ Configuración -->
          <section class="card">
            <header>⚙️ Configuración</header>
            <?php $regimenFiscalOptions = empresaRegimenFiscalOptions(); ?>
            <div class="content grid">
              <div class="field">
                <label for="estatus">Estatus</label>
                <select id="estatus" name="estatus">
                  <?php foreach ($estatusOptions as $option) : ?>
                    <option value="<?php echo htmlspecialchars($option, ENT_QUOTES, 'UTF-8'); ?>"
                      <?php echo empresaFormValue($formData, 'estatus') === $option ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($option, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="field">
                <label for="regimen_fiscal">Régimen fiscal</label>
                <select id="regimen_fiscal" name="regimen_fiscal">
                  <option value="">Selecciona tipo de empresa</option>
                  <?php foreach ($regimenFiscalOptions as $value => $label) : ?>
                    <option value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>"
                      <?php echo empresaFormValue($formData, 'regimen_fiscal') === $value ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="field col-span-2">
                <label for="notas">Notas / Observaciones</label>
                <textarea id="notas" name="notas" rows="4"
                  placeholder="Comentarios internos del área de vinculación..."><?php echo htmlspecialchars(empresaFormValue($formData, 'notas'), ENT_QUOTES, 'UTF-8'); ?></textarea>
              </div>
            </div>

            <div class="actions">
              <a href="empresa_view.php?id=<?php echo urlencode((string) $empresaId); ?>" class="btn secondary">Cancelar</a>
              <button type="submit" class="btn primary">💾 Guardar Cambios</button>
            </div>
          </section>
        </form>
      <?php endif; ?>
    </main>
  </div>
</body>

</html>
