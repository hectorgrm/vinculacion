<?php
declare(strict_types=1);

require_once __DIR__ . '/../../controller/EmpresaController.php';
require_once __DIR__ . '/../../common/functions/empresafunction.php';


use Residencia\Controller\EmpresaController;

$empresaController = new EmpresaController();

$formData = empresaFormDefaults();
$estatusOptions = empresaStatusOptions();
$errors = [];
$successMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = empresaSanitizeInput($_POST);
    $errors = empresaValidateData($formData);

    if ($errors === []) {
        try {
            $empresaId = $empresaController->createEmpresa($formData);
            $successMessage = 'La empresa se registró correctamente con el folio #' . $empresaId . '.';
            $formData = empresaFormDefaults();
        } catch (\Throwable $throwable) {
            if ($throwable instanceof PDOException) {
                $errorInfo = $throwable->errorInfo;

                if (is_array($errorInfo) && isset($errorInfo[1]) && (int) $errorInfo[1] === 1062) {
                    $errors[] = 'Ya existe una empresa registrada con el RFC proporcionado.';
                } else {
                    $errors[] = 'Ocurrió un error al registrar la empresa. Intenta nuevamente.';
                }
            } else {
                $errors[] = 'Ocurrió un error al registrar la empresa. Intenta nuevamente.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrar Empresa · Residencias Profesionales</title>

  <!-- Estilos globales del módulo de Residencias -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/empresas/empresalist.css">

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
          <?php if ($successMessage !== null) : ?>
          <div class="alert alert-success" style="margin-bottom:16px;">
            <?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?>
          </div>
          <?php endif; ?>

          <?php if ($errors !== []) : ?>
          <div class="alert alert-danger" style="margin-bottom:16px;">
            <p style="margin:0 0 8px 0; font-weight:600;">Por favor corrige los siguientes errores:</p>
            <ul style="margin:0 0 0 18px; padding:0;">
              <?php foreach ($errors as $error) : ?>
              <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php endif; ?>

          <form class="form" action="" method="post">
            <div class="grid">

              <!-- 🏢 Datos generales -->
              <h3 class="section-title">🏢 Información general</h3>
              <div class="field col-span-2">
                <label for="nombre" class="required">Nombre de la empresa *</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ej: Industrias Yakumo S.A. de C.V." value="<?php echo htmlspecialchars(empresaFormValue($formData, 'nombre'), ENT_QUOTES, 'UTF-8'); ?>" required />
              </div>

              <div class="field">
                <label for="rfc">RFC</label>
                <input type="text" id="rfc" name="rfc" placeholder="Ej: YAK930303CC3" maxlength="20" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'rfc'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="representante">Representante legal</label>
                <input type="text" id="representante" name="representante" placeholder="Ej: José Manuel Velador" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'representante'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="cargo_representante">Cargo del representante</label>
                <input type="text" id="cargo_representante" name="cargo_representante" placeholder="Ej: Director General" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'cargo_representante'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="sector">Sector o giro</label>
                <input type="text" id="sector" name="sector" placeholder="Ej: Educación / Social" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'sector'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="sitio_web">Sitio web</label>
                <input type="url" id="sitio_web" name="sitio_web" placeholder="https://www.empresa.mx" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'sitio_web'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <!-- 📞 Datos de contacto -->
              <h3 class="section-title">📞 Datos de contacto</h3>
              <div class="field">
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono" placeholder="Ej: (33) 1234 5678" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'telefono'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="contacto_nombre">Nombre del contacto</label>
                <input type="text" id="contacto_nombre" name="contacto_nombre" placeholder="Ej: Luis Pérez" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'contacto_nombre'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="contacto_email">Correo electrónico del contacto</label>
                <input type="email" id="contacto_email" name="contacto_email" placeholder="Ej: contacto@empresa.com" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'contacto_email'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <!-- 📍 Ubicación -->
              <h3 class="section-title">📍 Ubicación</h3>
              <div class="field">
                <label for="estado">Estado / Entidad</label>
                <input type="text" id="estado" name="estado" placeholder="Ej: Jalisco" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'estado'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="municipio">Municipio / Alcaldía</label>
                <input type="text" id="municipio" name="municipio" placeholder="Ej: Guadalajara" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'municipio'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="cp">Código Postal</label>
                <input type="text" id="cp" name="cp" placeholder="Ej: 44100" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'cp'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field col-span-2">
                <label for="direccion">Dirección (calle y número)</label>
                <input type="text" id="direccion" name="direccion" placeholder="Ej: Calle Independencia 321" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'direccion'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <!-- ⚙️ Configuración -->
              <h3 class="section-title">⚙️ Configuración</h3>
              <div class="field">
                <label for="estatus">Estatus</label>
                <select id="estatus" name="estatus">
                  <?php foreach ($estatusOptions as $option) : ?>
                  <option value="<?php echo htmlspecialchars($option, ENT_QUOTES, 'UTF-8'); ?>" <?php echo empresaFormValue($formData, 'estatus') === $option ? 'selected' : ''; ?>><?php echo htmlspecialchars($option, ENT_QUOTES, 'UTF-8'); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="field">
                <label for="regimen_fiscal">Régimen fiscal</label>
                <input type="text" id="regimen_fiscal" name="regimen_fiscal" placeholder="Ej: Persona Moral o Física" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'regimen_fiscal'), ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field col-span-2">
                <label for="notas">Notas / Observaciones</label>
                <textarea id="notas" name="notas" rows="3" placeholder="Comentarios internos del área de vinculación..."><?php echo htmlspecialchars(empresaFormValue($formData, 'notas'), ENT_QUOTES, 'UTF-8'); ?></textarea>
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
  <?php if ($successMessage !== null) : ?>
  <div id="empresa-success-toast" data-toast-message="<?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?>" hidden></div>
  <script src="../../assets/js/empresa-success-toast.js"></script>
  <?php endif; ?>
</body>

</html>
