<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../controller/EmpresaController.php';

use Serviciosocial\Controller\EmpresaController;

$searchTerm = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
$error = '';
$success = '';
$empresas = [];

if (isset($_GET['error'])) {
  $errorCode = (string) $_GET['error'];
  if ($errorCode === 'invalid') {
    $error = 'La empresa seleccionada no es válida.';
  } elseif ($errorCode === 'notfound') {
    $error = 'La empresa indicada no existe.';
  }
}

if (isset($_GET['created'])) {
  $success = 'La empresa se registró correctamente.';
} elseif (isset($_GET['deleted'])) {
  $success = 'La empresa se eliminó correctamente.';
}

try {
  $controller = new EmpresaController();
  $empresas = $controller->listEmpresas($searchTerm);
} catch (\Throwable $exception) {
  $error = 'No fue posible obtener la lista de empresas: ' . $exception->getMessage();
}

$estadoConfig = [
  'activo' => ['label' => 'Activo', 'class' => 'badge-activo'],
  'inactivo' => ['label' => 'Inactivo', 'class' => 'badge-inactivo'],
];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Empresas · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/empresas/empresaglobalstyles.css" />

</head>

<body>

  <!-- ===== HEADER ===== -->
  <header>
    <h1>Gestión de Empresas</h1>
    <p>Administra las empresas registradas en el sistema de Servicio Social</p>
  </header>

  <div class="container">

    <?php if ($error !== ''): ?>
      <div class="alert alert-error" role="alert">
        <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php elseif ($success !== ''): ?>
      <div class="alert alert-success" role="status">
        <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php endif; ?>

    <!-- ===== BARRA DE ACCIONES ===== -->
    <div class="search-bar">
       <a href="../../index.php" class="btn-back" title="Regresar al panel">⬅ Regresar</a>
      <form action="" method="get">
        <input type="text" name="q" placeholder="Buscar empresa por nombre o contacto..."
          value="<?php echo htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8'); ?>" />
        <button type="submit" class="btn btn-primary">Buscar</button>
      </form>
      <a href="empresa_add.php" class="btn btn-success">+ Nueva Empresa</a>
    </div>

    <!-- ===== TABLA ===== -->
    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Contacto</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($error === '' && !empty($empresas)): ?>
            <?php foreach ($empresas as $empresa): ?>
              <tr>
                <td data-label="ID"><?php echo (int) $empresa['id']; ?></td>
                <td data-label="Nombre">
                  <?php echo htmlspecialchars($empresa['nombre'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                </td>
                <td data-label="Contacto">
                  <?php echo htmlspecialchars($empresa['contacto_nombre'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
                </td>
                <td data-label="Email">
                  <?php echo htmlspecialchars($empresa['contacto_email'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
                </td>
                <td data-label="Teléfono">
                  <?php echo htmlspecialchars($empresa['telefono'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
                </td>
                <td data-label="Estado">
                  <?php
                  $estado = strtolower((string) ($empresa['estado'] ?? ''));
                  $config = $estadoConfig[$estado] ?? ['label' => ucfirst($estado), 'class' => ''];
                  ?>
                  <span class="badge <?php echo htmlspecialchars($config['class'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($config['label'], ENT_QUOTES, 'UTF-8'); ?>
                  </span>
                </td>
                <td data-label="Acciones" class="actions">
                  <a href="convenios_list.php?empresa_id=<?php echo (int) $empresa['id']; ?>"
                    class="btn btn-secondary btn-sm">Ver convenios</a>
                  <a href="empresa_edit.php?id=<?php echo (int) $empresa['id']; ?>"
                    class="btn btn-warning btn-sm">Editar</a>
                  <a href="empresa_delete.php?id=<?php echo (int) $empresa['id']; ?>"
                    class="btn btn-danger btn-sm">Eliminar</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7">
                <div class="empty-state">
                  <?php echo $error !== ''
                    ? 'No se pudo cargar la información de empresas.'
                    : 'No hay empresas registradas.'; ?>
                </div>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>

</body>

</html>