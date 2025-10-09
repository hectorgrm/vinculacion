<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../model/EstudianteModel.php';
require_once __DIR__ . '/../../../common/model/db.php';

use Serviciosocial\Model\EstudianteModel;
use Common\Model\Database;

// ✅ Validar sesión y rol
$user = $_SESSION['user'] ?? null;
$allowedRoles = ['ss_admin'];

if (!is_array($user) || !in_array(strtolower((string)($user['role'] ?? '')), $allowedRoles, true)) {
    header('Location: ../../../common/login.php?module=serviciosocial&error=unauthorized');
    exit;
}

// ✅ Obtener estudiantes desde el modelo
$error = '';
$estudiantes = [];
$feedbackMessage = '';
$feedbackClass = 'alert-success';

try {
    $pdo = Database::getConnection();
    $model = new EstudianteModel($pdo);
    $estudiantes = $model->getAll();
} catch (Throwable $exception) {
    $error = 'No fue posible obtener la lista de estudiantes: ' . $exception->getMessage();
}

// ✅ Mensajes de feedback
if (isset($_GET['success'])) {
    $feedbackMessage = 'El estudiante fue registrado correctamente.';
} elseif (isset($_GET['updated'])) {
    $feedbackMessage = 'La información del estudiante se actualizó correctamente.';
} elseif (isset($_GET['deleted'])) {
    $feedbackMessage = 'El estudiante fue eliminado correctamente.';
} elseif (isset($_GET['error'])) {
    if ($_GET['error'] === 'invalid') {
        $feedbackMessage = 'La solicitud para editar el estudiante no es válida.';
    } elseif ($_GET['error'] === 'notfound') {
        $feedbackMessage = 'El estudiante solicitado no se encontró en el sistema.';
    }
    if ($feedbackMessage !== '') $feedbackClass = 'alert-error';
}

$searchValue = isset($_GET['query']) ? trim((string)$_GET['query']) : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Estudiantes · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/sidebar.css">
  <link rel="stylesheet" href="../../assets/css/gestestudiante/estudianteliststyles.css">
</head>
<body>
  <div class="app">

    <!-- 📊 Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- 📜 Contenido Principal -->
    <main class="main">
      <header class="topbar">
        <h2>Gestión de Estudiantes</h2>
        <button class="btn primary" onclick="window.location.href='estudiante_add.php'">+ Nuevo Estudiante</button>
      </header>

      <!-- ✅ Mensajes de feedback -->
      <?php if ($feedbackMessage !== ''): ?>
        <div class="alert <?php echo $feedbackClass; ?>" role="alert">
          <?php echo htmlspecialchars($feedbackMessage, ENT_QUOTES, 'UTF-8'); ?>
        </div>
      <?php endif; ?>

      <?php if ($error !== ''): ?>
        <div class="alert alert-error" role="alert">
          <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
        </div>
      <?php endif; ?>

      <!-- 🔍 Barra de búsqueda -->
      <section class="card">
        <header>Listado de Estudiantes</header>
        <div class="top-actions">
          <form class="search-bar" method="get" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>">
            <input type="text" name="query" placeholder="Buscar por nombre, matrícula o carrera" value="<?php echo htmlspecialchars($searchValue, ENT_QUOTES, 'UTF-8'); ?>">
            <button type="submit">Buscar</button>
          </form>
        </div>

        <!-- 📋 Tabla de estudiantes -->
        <div class="content">
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
                <?php foreach ($estudiantes as $estudiante): ?>
                  <tr>
                    <td><?php echo (int)$estudiante['id']; ?></td>
                    <td><?php echo htmlspecialchars($estudiante['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($estudiante['matricula'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($estudiante['carrera'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($estudiante['email'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($estudiante['telefono'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="actions">
                      <a class="view" href="estudiante_view.php?id=<?php echo (int)$estudiante['id']; ?>">👁️ Ver</a>
                      <a class="edit" href="estudiante_edit.php?id=<?php echo (int)$estudiante['id']; ?>">✏️ Editar</a>
                      <a class="delete" href="estudiante_delete.php?id=<?php echo (int)$estudiante['id']; ?>" onclick="return confirm('¿Seguro que deseas eliminar este estudiante?');">🗑️ Eliminar</a>
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
        </div>
      </section>
    </main>
  </div>

  <footer>
    &copy; <?php echo date('Y'); ?> Servicio Social - Instituto Tecnológico de Villahermosa
  </footer>
</body>
</html>
