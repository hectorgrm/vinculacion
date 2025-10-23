<?php
declare(strict_types=1);

require_once __DIR__ . '/../../controller/EmpresaController.php';
require_once __DIR__ . '/../../common/functions/empresafunction.php';

use Residencia\Controller\EmpresaController;

$estatusOptions = empresaStatusOptions();
$formData = empresaFormDefaults();
$errors = [];
$successMessage = null;
$controllerError = null;
$loadError = null;
$empresa = null;
$empresaController = null;

$empresaIdParam = $_GET['id'] ?? ($_POST['empresa_id'] ?? null);
$empresaId = null;

if ($empresaIdParam === null) {
    $loadError = 'No se especificó la empresa que deseas editar.';
} elseif (is_string($empresaIdParam) && preg_match('/^\d+$/', $empresaIdParam) === 1) {
    $empresaId = (int) $empresaIdParam;

    if ($empresaId <= 0) {
        $loadError = 'El identificador proporcionado no es válido.';
    }
} else {
    $loadError = 'El identificador proporcionado no es válido.';
}

if ($loadError === null) {
    try {
        $empresaController = new EmpresaController();
    } catch (\PDOException | \RuntimeException $exception) {
        $controllerError = 'No se pudo establecer conexión con la base de datos. Intenta nuevamente más tarde.';
    }
}

if ($controllerError === null && $loadError === null && $empresaController instanceof EmpresaController && $empresaId !== null) {
    try {
        $empresa = $empresaController->getEmpresaById($empresaId);
        $formData = empresaHydrateForm($formData, $empresa);
    } catch (\RuntimeException $exception) {
        $loadError = $exception->getMessage();
    }
}

if ($controllerError === null
    && $loadError === null
    && $empresaController instanceof EmpresaController
    && $empresa !== null
    && $_SERVER['REQUEST_METHOD'] === 'POST'
) {
    $formData = empresaSanitizeInput($_POST);
    $errors = empresaValidateData($formData);

    if ($errors === []) {
        try {
            $empresaController->updateEmpresa($empresaId, $formData);
            $successMessage = 'La información de la empresa se actualizó correctamente.';

            $empresa = $empresaController->getEmpresaById($empresaId);
            $formData = empresaHydrateForm(empresaFormDefaults(), $empresa);
        } catch (\RuntimeException $exception) {
            $previous = $exception->getPrevious();

            if ($previous instanceof \PDOException) {
                $errorInfo = $previous->errorInfo;

                if (is_array($errorInfo) && isset($errorInfo[1]) && (int) $errorInfo[1] === 1062) {
                    $duplicateDetail = isset($errorInfo[2]) && is_string($errorInfo[2])
                        ? $errorInfo[2]
                        : '';

                    if ($duplicateDetail !== '' && stripos($duplicateDetail, 'numero_control') !== false) {
                        $errors[] = 'Ya existe una empresa registrada con el número de control proporcionado.';
                    } elseif ($duplicateDetail !== '' && stripos($duplicateDetail, 'rfc') !== false) {
                        $errors[] = 'Ya existe una empresa registrada con el RFC proporcionado.';
                    } else {
                        $errors[] = 'Ya existe una empresa registrada con la información proporcionada.';
                    }
                } else {
                    $errors[] = 'Ocurrió un error al actualizar la empresa. Intenta nuevamente.';
                }
            } else {
                $errors[] = $exception->getMessage();
            }
        } catch (\Throwable $throwable) {
            $errors[] = 'Ocurrió un error al actualizar la empresa. Intenta nuevamente.';
        }
    }
}

$empresaNombre = $formData['nombre'] ?? '';

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>✏️ Editar Empresa · Residencias Profesionales</title>

  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <link rel="stylesheet" href="../../assets/css/empresas/empresaedit.css" />
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
              <input id="numero_control" name="numero_control" type="text" maxlength="20" placeholder="Ej: EMP-0001" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'numero_control'), ENT_QUOTES, 'UTF-8'); ?>" />
            </div>
            <div class="field">
              <label for="nombre" class="required">Nombre de la empresa *</label>
              <input id="nombre" name="nombre" type="text" required placeholder="Ej: Casa del Barrio" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'nombre'), ENT_QUOTES, 'UTF-8'); ?>" />
            </div>

            <div class="field">
              <label for="rfc">RFC</label>
              <input id="rfc" name="rfc" type="text" maxlength="20" placeholder="Ej: CDB810101AA1" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'rfc'), ENT_QUOTES, 'UTF-8'); ?>" />
            </div>

            <div class="field">
              <label for="representante">Representante legal</label>
              <input id="representante" name="representante" type="text" placeholder="Ej: José Manuel Velador" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'representante'), ENT_QUOTES, 'UTF-8'); ?>" />
            </div>

            <div class="field">
              <label for="cargo_representante">Cargo del representante</label>
              <input id="cargo_representante" name="cargo_representante" type="text" placeholder="Ej: Director General" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'cargo_representante'), ENT_QUOTES, 'UTF-8'); ?>" />
            </div>

            <div class="field">
              <label for="sector">Sector / Giro</label>
              <input id="sector" name="sector" type="text" placeholder="Ej: Educación / Social" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'sector'), ENT_QUOTES, 'UTF-8'); ?>" />
            </div>

            <div class="field">
              <label for="sitio_web">Sitio web</label>
              <input id="sitio_web" name="sitio_web" type="url" placeholder="https://www.empresa.mx" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'sitio_web'), ENT_QUOTES, 'UTF-8'); ?>" />
            </div>
          </div>
        </section>

        <!-- 📬 Contacto y Dirección -->
        <section class="card">
          <header>📬 Contacto y Dirección</header>
          <div class="content grid">
            <div class="field">
              <label for="contacto_nombre">Nombre de contacto</label>
              <input id="contacto_nombre" name="contacto_nombre" type="text" placeholder="Ej: Responsable RH" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'contacto_nombre'), ENT_QUOTES, 'UTF-8'); ?>" />
            </div>

            <div class="field">
              <label for="contacto_email">Correo electrónico</label>
              <input id="contacto_email" name="contacto_email" type="email" placeholder="contacto@empresa.mx" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'contacto_email'), ENT_QUOTES, 'UTF-8'); ?>" />
            </div>

            <div class="field">
              <label for="telefono">Teléfono</label>
              <input id="telefono" name="telefono" type="tel" placeholder="(33) 1234 5678" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'telefono'), ENT_QUOTES, 'UTF-8'); ?>" />
            </div>

            <div class="field">
              <label for="estado">Estado</label>
              <input id="estado" name="estado" type="text" placeholder="Ej: Jalisco" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'estado'), ENT_QUOTES, 'UTF-8'); ?>" />
            </div>

            <div class="field">
              <label for="municipio">Municipio / Alcaldía</label>
              <input id="municipio" name="municipio" type="text" placeholder="Ej: Guadalajara" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'municipio'), ENT_QUOTES, 'UTF-8'); ?>" />
            </div>

            <div class="field">
              <label for="cp">Código Postal</label>
              <input id="cp" name="cp" type="text" placeholder="Ej: 44100" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'cp'), ENT_QUOTES, 'UTF-8'); ?>" />
            </div>

            <div class="field col-span-2">
              <label for="direccion">Dirección (calle y número)</label>
              <input id="direccion" name="direccion" type="text" placeholder="Ej: Calle Independencia 321" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'direccion'), ENT_QUOTES, 'UTF-8'); ?>" />
            </div>
          </div>
        </section>

        <!-- ⚙️ Configuración -->
        <section class="card">
          <header>⚙️ Configuración</header>
          <div class="content grid">
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
              <input id="regimen_fiscal" name="regimen_fiscal" type="text" placeholder="Opcional" value="<?php echo htmlspecialchars(empresaFormValue($formData, 'regimen_fiscal'), ENT_QUOTES, 'UTF-8'); ?>" />
            </div>

            <div class="field col-span-2">
              <label for="notas">Notas / Observaciones</label>
              <textarea id="notas" name="notas" rows="4" placeholder="Comentarios internos del área de vinculación..."><?php echo htmlspecialchars(empresaFormValue($formData, 'notas'), ENT_QUOTES, 'UTF-8'); ?></textarea>
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
