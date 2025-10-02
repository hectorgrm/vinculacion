<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../controller/EmpresaController.php';

use Serviciosocial\Controller\EmpresaController;

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$error = '';
$success = '';
$empresa = null;

if ($id <= 0) {
    $error = 'Identificador de empresa no válido.';
} else {
    try {
        $controller = new EmpresaController();
        $empresa = $controller->findEmpresa($id);

        if ($empresa === null) {
            $error = 'La empresa seleccionada no existe.';
        }
    } catch (\Throwable $exception) {
        $error = 'No fue posible cargar la empresa: ' . $exception->getMessage();
    }
}

$formValues = [
    'nombre' => $empresa['nombre'] ?? '',
    'contacto_nombre' => $empresa['contacto_nombre'] ?? '',
    'contacto_email' => $empresa['contacto_email'] ?? '',
    'telefono' => $empresa['telefono'] ?? '',
    'direccion' => $empresa['direccion'] ?? '',
    'estado' => $empresa['estado'] ?? 'activo',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $error === '' && isset($controller)) {
    $postData = [
        'nombre' => $_POST['nombre'] ?? '',
        'contacto_nombre' => $_POST['contacto_nombre'] ?? '',
        'contacto_email' => $_POST['contacto_email'] ?? '',
        'telefono' => $_POST['telefono'] ?? '',
        'direccion' => $_POST['direccion'] ?? '',
        'estado' => $_POST['estado'] ?? '',
    ];

    $formValues = array_merge($formValues, $postData);

    try {
        $controller->updateEmpresa($id, $postData);
        $success = 'La empresa se actualizó correctamente.';
        $empresa = $controller->findEmpresa($id);
        if ($empresa !== null) {
            $formValues = array_merge($formValues, [
                'nombre' => $empresa['nombre'] ?? '',
                'contacto_nombre' => $empresa['contacto_nombre'] ?? '',
                'contacto_email' => $empresa['contacto_email'] ?? '',
                'telefono' => $empresa['telefono'] ?? '',
                'direccion' => $empresa['direccion'] ?? '',
                'estado' => $empresa['estado'] ?? 'activo',
            ]);
        }
    } catch (\Throwable $exception) {
        $error = 'No fue posible actualizar la empresa: ' . $exception->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Empresa · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/empresas/empresaglobalstyles.css" />

</head>
<body>

  <!-- ===== HEADER ===== -->
  <header>
    <h1>Editar Empresa</h1>
    <p>Modifica la información de la empresa registrada en el sistema</p>
  </header>

  <div class="container">

    <?php if ($error !== ''): ?>
      <div class="alert alert-error" role="alert">
        <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php elseif ($success !== ''): ?>
      <div class="alert alert-success" role="alert">
        <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php endif; ?>

    <!-- ===== FORMULARIO ===== -->
    <div class="form-card">
      <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" method="post">

        <div class="form-group">
          <label for="nombre">Nombre de la Empresa <span class="required">*</span></label>
          <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($formValues['nombre'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required />
        </div>

        <div class="form-group">
          <label for="contacto_nombre">Nombre del Contacto</label>
          <input type="text" id="contacto_nombre" name="contacto_nombre" value="<?php echo htmlspecialchars($formValues['contacto_nombre'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
        </div>

        <div class="form-group">
          <label for="contacto_email">Email de Contacto</label>
          <input type="email" id="contacto_email" name="contacto_email" value="<?php echo htmlspecialchars($formValues['contacto_email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
        </div>

        <div class="form-group">
          <label for="telefono">Teléfono</label>
          <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($formValues['telefono'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
        </div>

        <div class="form-group">
          <label for="direccion">Dirección</label>
          <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($formValues['direccion'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
        </div>

        <div class="form-group">
          <label for="estado">Estado</label>
          <select id="estado" name="estado">
            <option value="activo" <?php echo (isset($formValues['estado']) && $formValues['estado'] === 'activo') ? 'selected' : ''; ?>>Activo</option>
            <option value="inactivo" <?php echo (isset($formValues['estado']) && $formValues['estado'] === 'inactivo') ? 'selected' : ''; ?>>Inactivo</option>
          </select>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-success" <?php echo $error !== '' && $success === '' ? 'disabled' : ''; ?>>Guardar Cambios</button>
          <a href="empresa_list.php" class="btn btn-secondary">Cancelar</a>
        </div>
      </form>
    </div>

  </div>

</body>
</html>
