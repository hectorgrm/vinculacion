<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../model/EstudianteModel.php';
require_once __DIR__ . '/../../../common/model/db.php';

use Serviciosocial\Model\EstudianteModel;
use Common\Model\Database;

// Validar sesión y rol
$user = $_SESSION['user'] ?? null;
$allowedRoles = ['ss_admin'];

if (!is_array($user) || !in_array(strtolower((string)($user['role'] ?? '')), $allowedRoles, true)) {
    header('Location: ../../../common/login.php?module=serviciosocial&error=unauthorized');
    exit;
}

// Obtener estudiantes desde el modelo
$error = '';
$estudiantes = [];

try {
    $pdo = Database::getConnection();
    $model = new EstudianteModel($pdo);
    $estudiantes = $model->getAll();
} catch (Throwable $exception) {
    $error = 'No fue posible obtener la lista de estudiantes: ' . $exception->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Estudiantes</title>
  <link rel="stylesheet" href="../../assets/serviciosocialstyles.css">
</head>
<body>
  <header>
    <h1>Gestión de Estudiantes</h1>
    <p>Hola, <?php echo htmlspecialchars((string)($user['name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
  </header>
  
  <main>
    <?php if ($error !== ''): ?>
      <div class="alert-error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <div class="top-actions">
      <a href="../../index.php" class="btn-back">⬅ Regresar</a>
      <a href="estudiante_add.php" class="btn-add">➕ Dar de Alta Estudiante</a>
    </div>

    <table class="styled-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Matrícula</th>
          <th>Carrera</th>
          <th>Email</th>
          <th>Teléfono</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($estudiantes)): ?>
          <?php foreach ($estudiantes as $e): ?>
            <tr>
              <td><?php echo (int)$e['id']; ?></td>
              <td><?php echo htmlspecialchars($e['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($e['matricula'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($e['carrera'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($e['email'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($e['telefono'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
              <td class="actions">
                <a href="estudiante_view.php?id=<?php echo (int)$e['id']; ?>">Ver</a>
                <a href="estudiante_edit.php?id=<?php echo (int)$e['id']; ?>">Editar</a>
                <a href="estudiante_delete.php?id=<?php echo (int)$e['id']; ?>" onclick="return confirm('¿Seguro que deseas eliminar este estudiante?')">Eliminar</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="7">No hay estudiantes registrados.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </main>
</body>
</html>
