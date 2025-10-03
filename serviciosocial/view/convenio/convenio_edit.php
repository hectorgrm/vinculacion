<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../controller/ConvenioController.php';

use Serviciosocial\Controller\ConvenioController;

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$controller = null;
$empresas = [];
$convenio = null;
$generalError = '';
$errors = [];

$formData = [
    'ss_empresa_id' => '',
    'estatus' => 'pendiente',
    'fecha_inicio' => '',
    'fecha_fin' => '',
    'version_actual' => '',
];

if ($id <= 0) {
    $generalError = 'Identificador de convenio no válido.';
} else {
    try {
        $controller = new ConvenioController();
        $empresas = $controller->listEmpresas();
        $convenio = $controller->findById($id);

        if ($convenio === null) {
            $generalError = 'El convenio solicitado no existe.';
        } else {
            $formData = [
                'ss_empresa_id' => (string) ($convenio['ss_empresa_id'] ?? ''),
                'estatus' => (string) ($convenio['estatus'] ?? 'pendiente'),
                'fecha_inicio' => (string) ($convenio['fecha_inicio'] ?? ''),
                'fecha_fin' => (string) ($convenio['fecha_fin'] ?? ''),
                'version_actual' => (string) ($convenio['version_actual'] ?? ''),
            ];
        }
    } catch (\Throwable $exception) {
        $generalError = 'No fue posible cargar la información del convenio: ' . $exception->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $generalError === '' && $controller instanceof ConvenioController) {
    $formData['ss_empresa_id'] = trim((string) ($_POST['ss_empresa_id'] ?? ''));
    $formData['estatus'] = trim((string) ($_POST['estatus'] ?? 'pendiente'));
    $formData['fecha_inicio'] = trim((string) ($_POST['fecha_inicio'] ?? ''));
    $formData['fecha_fin'] = trim((string) ($_POST['fecha_fin'] ?? ''));
    $formData['version_actual'] = trim((string) ($_POST['version_actual'] ?? ''));

    try {
        $controller->update($id, $formData);
        header('Location: convenio_list.php?updated=1');
        exit;
    } catch (\InvalidArgumentException $invalidArgumentException) {
        $errors[] = $invalidArgumentException->getMessage();
    } catch (\Throwable $exception) {
        $errors[] = 'No fue posible actualizar el convenio: ' . $exception->getMessage();
    }
}

/**
 * @param string $value
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$estatusConfig = [
    'pendiente' => ['label' => 'Pendiente', 'class' => 'pendiente'],
    'vigente' => ['label' => 'Vigente', 'class' => 'vigente'],
    'vencido' => ['label' => 'Vencido', 'class' => 'vencido'],
];

$estatusActual = strtolower($formData['estatus'] ?? 'pendiente');
$estatusInfo = $estatusConfig[$estatusActual] ?? ['label' => ucfirst($estatusActual), 'class' => ''];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Convenio - Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/convenios/conveniostyles.css" />
</head>
<body>

  <header>
    <h1>Editar Convenio</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="convenio_list.php">Convenios</a>
      <span>›</span>
      <span>Editar</span>
    </nav>
  </header>

  <main>
    <section class="card">
      <h2>Actualizar información del convenio</h2>

      <?php if ($generalError !== ''): ?>
        <div class="alert alert-error" role="alert">
          <?php echo e($generalError); ?>
        </div>
      <?php else: ?>
        <?php if (!empty($errors)): ?>
          <div class="alert alert-error" role="alert">
            <ul>
              <?php foreach ($errors as $error): ?>
                <li><?php echo e($error); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php else: ?>
          <div class="alert alert-success">
            Estás editando un convenio existente. Asegúrate de revisar todos los datos antes de guardar los cambios.
          </div>
        <?php endif; ?>

        <form action="<?php echo e((string) ($_SERVER['REQUEST_URI'] ?? '')); ?>" method="post" class="form">

          <!-- Sección 1: Información general -->
          <div class="form-section">
            <h3>📁 Información General</h3>

            <div class="field">
              <label for="ss_empresa_id">Empresa relacionada <span class="required">*</span></label>
              <select id="ss_empresa_id" name="ss_empresa_id" required <?php echo empty($empresas) ? 'disabled' : ''; ?>>
                <option value="">-- Selecciona la empresa --</option>
                <?php foreach ($empresas as $empresa): ?>
                  <option value="<?php echo (int) ($empresa['id'] ?? 0); ?>" <?php echo ((string) ($empresa['id'] ?? '') === $formData['ss_empresa_id']) ? 'selected' : ''; ?>>
                    <?php echo e((string) ($empresa['nombre'] ?? '')); ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <?php if (empty($empresas)): ?>
                <div class="hint">No hay empresas registradas. Agrega una empresa antes de actualizar el convenio.</div>
              <?php else: ?>
                <div class="hint">Si cambias la empresa, asegúrate que sea la correcta para este convenio.</div>
              <?php endif; ?>
            </div>

            <div class="field">
              <label for="estatus">Estatus <span class="required">*</span></label>
              <select id="estatus" name="estatus" required>
                <option value="">-- Selecciona el estatus --</option>
                <option value="pendiente" <?php echo $formData['estatus'] === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                <option value="vigente" <?php echo $formData['estatus'] === 'vigente' ? 'selected' : ''; ?>>Vigente</option>
                <option value="vencido" <?php echo $formData['estatus'] === 'vencido' ? 'selected' : ''; ?>>Vencido</option>
              </select>
              <div class="hint">Cambia el estado si el convenio ha caducado o sigue activo.</div>
            </div>
          </div>

          <!-- Sección 2: Vigencia -->
          <div class="form-section">
            <h3>📅 Vigencia del Convenio</h3>
            <div class="form-grid">
              <div class="field">
                <label for="fecha_inicio">Fecha de inicio</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo e($formData['fecha_inicio']); ?>" />
                <div class="hint">Fecha desde la cual el convenio entra en vigor.</div>
              </div>

              <div class="field">
                <label for="fecha_fin">Fecha de fin</label>
                <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo e($formData['fecha_fin']); ?>" />
                <div class="hint">Fecha en la que finaliza la vigencia actual del convenio.</div>
              </div>
            </div>

            <div class="field">
              <label for="version_actual">Versión del convenio</label>
              <input type="text" id="version_actual" name="version_actual" value="<?php echo e($formData['version_actual']); ?>" />
              <div class="hint">Úsalo para controlar cambios o actualizaciones en el documento.</div>
            </div>
          </div>

          <!-- Sección 3: Estado actual -->
          <div class="form-section">
            <h3>📊 Estado del Convenio</h3>
            <p>Estado actual: <span class="badge <?php echo e($estatusInfo['class']); ?>"><?php echo e($estatusInfo['label']); ?></span></p>
            <div class="hint">Este estado se actualizará automáticamente al cambiar el estatus y guardar los cambios.</div>
          </div>

          <!-- Acciones -->
          <div class="actions">
            <button type="submit" class="btn btn-primary" <?php echo empty($empresas) ? 'disabled' : ''; ?>>💾 Guardar Cambios</button>
            <a href="convenio_list.php" class="btn btn-secondary">↩️ Cancelar</a>
          </div>
        </form>
      <?php endif; ?>
    </section>
  </main>

</body>
</html>
