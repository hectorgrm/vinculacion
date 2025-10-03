<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../controller/ConvenioController.php';

use Serviciosocial\Controller\ConvenioController;

$controller = null;
$empresas = [];
$errors = [];
$generalError = '';

$formData = [
    'ss_empresa_id' => '',
    'estatus' => 'pendiente',
    'fecha_inicio' => '',
    'fecha_fin' => '',
    'version_actual' => '',
];

try {
    $controller = new ConvenioController();
    $empresas = $controller->listEmpresas();
} catch (\Throwable $exception) {
    $generalError = 'No fue posible preparar el formulario: ' . $exception->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['ss_empresa_id'] = trim((string) ($_POST['ss_empresa_id'] ?? ''));
    $formData['estatus'] = trim((string) ($_POST['estatus'] ?? 'pendiente'));
    $formData['fecha_inicio'] = trim((string) ($_POST['fecha_inicio'] ?? ''));
    $formData['fecha_fin'] = trim((string) ($_POST['fecha_fin'] ?? ''));
    $formData['version_actual'] = trim((string) ($_POST['version_actual'] ?? ''));

    if ($controller instanceof ConvenioController && $generalError === '') {
        try {
            $controller->create($formData);
            header('Location: convenio_list.php?created=1');
            exit;
        } catch (\InvalidArgumentException $invalidArgumentException) {
            $errors[] = $invalidArgumentException->getMessage();
        } catch (\Throwable $exception) {
            $errors[] = 'No fue posible registrar el convenio: ' . $exception->getMessage();
        }
    }
}

/**
 * @param string $value
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrar Nuevo Convenio - Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/convenios/conveniostyles.css" />
</head>
<body>

  <header>
    <h1>Registrar Nuevo Convenio</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="convenio_list.php">Convenios</a>
      <span>›</span>
      <span>Nuevo Convenio</span>
    </nav>
  </header>

  <main>
<section class="card">
  <h2>Registrar Nuevo Convenio</h2>

  <?php if ($generalError !== ''): ?>
    <div class="alert alert-error" role="alert">
      <?php echo e($generalError); ?>
    </div>
  <?php elseif (!empty($errors)): ?>
    <div class="alert alert-error" role="alert">
      <ul>
        <?php foreach ($errors as $error): ?>
          <li><?php echo e($error); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form action="<?php echo e((string) ($_SERVER['PHP_SELF'] ?? '')); ?>" method="post" class="form">

    <!-- Sección 1: Información General -->
    <div class="form-section">
      <h3>📁 Información General</h3>

      <div class="field">
        <label for="ss_empresa_id">Empresa relacionada <span class="required">*</span></label>
        <select id="ss_empresa_id" name="ss_empresa_id" required <?php echo ($generalError !== '' || empty($empresas)) ? 'disabled' : ''; ?>>
          <option value="">-- Selecciona la empresa --</option>
          <?php foreach ($empresas as $empresa): ?>
            <option value="<?php echo (int) ($empresa['id'] ?? 0); ?>" <?php echo ((string) ($empresa['id'] ?? '') === $formData['ss_empresa_id']) ? 'selected' : ''; ?>>
              <?php echo e((string) ($empresa['nombre'] ?? '')); ?>
            </option>
          <?php endforeach; ?>
        </select>
        <?php if (empty($empresas) && $generalError === ''): ?>
          <div class="hint">No hay empresas registradas. Agrega una empresa antes de crear un convenio.</div>
        <?php else: ?>
          <div class="hint">La empresa debe existir previamente para poder asociar el convenio.</div>
        <?php endif; ?>
      </div>

      <div class="field">
        <label for="estatus">Estatus <span class="required">*</span></label>
        <select id="estatus" name="estatus" required <?php echo $generalError !== '' ? 'disabled' : ''; ?>>
          <option value="">-- Selecciona el estatus --</option>
          <option value="pendiente" <?php echo $formData['estatus'] === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
          <option value="vigente" <?php echo $formData['estatus'] === 'vigente' ? 'selected' : ''; ?>>Vigente</option>
          <option value="vencido" <?php echo $formData['estatus'] === 'vencido' ? 'selected' : ''; ?>>Vencido</option>
        </select>
      </div>
    </div>

    <!-- Sección 2: Vigencia -->
    <div class="form-section">
      <h3>📅 Vigencia del Convenio</h3>
      <div class="form-grid">
        <div class="field">
          <label for="fecha_inicio">Fecha de inicio</label>
          <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo e($formData['fecha_inicio']); ?>" <?php echo $generalError !== '' ? 'disabled' : ''; ?> />
        </div>
        <div class="field">
          <label for="fecha_fin">Fecha de fin</label>
          <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo e($formData['fecha_fin']); ?>" <?php echo $generalError !== '' ? 'disabled' : ''; ?> />
        </div>
      </div>

      <div class="field">
        <label for="version_actual">Versión del convenio</label>
        <input type="text" id="version_actual" name="version_actual" placeholder="Ej: v1.0" value="<?php echo e($formData['version_actual']); ?>" <?php echo $generalError !== '' ? 'disabled' : ''; ?> />
        <div class="hint">Puedes usar este campo para llevar control de revisiones del convenio.</div>
      </div>
    </div>

    <!-- Acciones -->
    <div class="actions">
      <button type="submit" class="btn btn-primary" <?php echo ($generalError !== '' || empty($empresas)) ? 'disabled' : ''; ?>>💾 Guardar Convenio</button>
      <a href="convenio_list.php" class="btn btn-secondary">↩️ Cancelar</a>
    </div>
  </form>
</section>

  </main>

</body>
</html>
