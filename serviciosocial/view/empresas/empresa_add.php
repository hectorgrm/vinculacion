<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../controller/EmpresaController.php';

use Serviciosocial\Controller\EmpresaController;

$controller = null;
$errors = [];
$generalError = '';

$formData = [
    'nombre' => '',
    'contacto_nombre' => '',
    'contacto_email' => '',
    'telefono' => '',
    'direccion' => '',
    'estado' => 'activo',
];

try {
    $controller = new EmpresaController();
} catch (\Throwable $exception) {
    $generalError = 'No fue posible preparar el formulario: ' . $exception->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (array_keys($formData) as $key) {
        if ($key === 'estado') {
            $formData[$key] = isset($_POST[$key]) ? trim((string) $_POST[$key]) : 'activo';
            if ($formData[$key] === '') {
                $formData[$key] = 'activo';
            }
        } else {
            $formData[$key] = trim((string) ($_POST[$key] ?? ''));
        }
    }

    if ($controller instanceof EmpresaController && $generalError === '') {
        try {
            $controller->createEmpresa($formData);
            header('Location: empresa_list.php?created=1');
            exit;
        } catch (\InvalidArgumentException $invalidArgumentException) {
            $errors[] = $invalidArgumentException->getMessage();
        } catch (\Throwable $exception) {
            $errors[] = 'No fue posible registrar la empresa: ' . $exception->getMessage();
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
  <title>Registrar Nueva Empresa · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/empresas/empresaglobalstyles.css" />
</head>
<body>

  <!-- ===== HEADER ===== -->
  <header>
    <h1>Registrar Nueva Empresa</h1>
    <p>Completa el siguiente formulario para agregar una nueva empresa al sistema</p>
  </header>

  <div class="container">

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

    <!-- ===== FORMULARIO ===== -->
    <div class="form-card">
      <form action="<?php echo e((string) ($_SERVER['PHP_SELF'] ?? '')); ?>" method="post">

        <div class="form-group">
          <label for="nombre">Nombre de la Empresa <span class="required">*</span></label>
          <input type="text" id="nombre" name="nombre" placeholder="Ej: Universidad Tecnológica del Centro" value="<?php echo e($formData['nombre']); ?>" required />
        </div>

        <div class="form-group">
          <label for="contacto_nombre">Nombre del Contacto</label>
          <input type="text" id="contacto_nombre" name="contacto_nombre" placeholder="Ej: Dra. María López" value="<?php echo e($formData['contacto_nombre']); ?>" />
        </div>

        <div class="form-group">
          <label for="contacto_email">Email de Contacto</label>
          <input type="email" id="contacto_email" name="contacto_email" placeholder="Ej: contacto@empresa.com" value="<?php echo e($formData['contacto_email']); ?>" />
        </div>

        <div class="form-group">
          <label for="telefono">Teléfono</label>
          <input type="text" id="telefono" name="telefono" placeholder="Ej: (33) 1234 5678" value="<?php echo e($formData['telefono']); ?>" />
        </div>

        <div class="form-group">
          <label for="direccion">Dirección</label>
          <input type="text" id="direccion" name="direccion" placeholder="Ej: Av. Principal 123, Ciudad A" value="<?php echo e($formData['direccion']); ?>" />
        </div>

        <div class="form-group">
          <label for="estado">Estado</label>
          <select id="estado" name="estado">
            <option value="activo" <?php echo $formData['estado'] === 'activo' ? 'selected' : ''; ?>>Activo</option>
            <option value="inactivo" <?php echo $formData['estado'] === 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
          </select>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-success" <?php echo ($generalError !== '') ? 'disabled' : ''; ?>>Registrar Empresa</button>
          <a href="empresa_list.php" class="btn btn-secondary">Cancelar</a>
        </div>
      </form>
    </div>

  </div>

</body>
</html>
