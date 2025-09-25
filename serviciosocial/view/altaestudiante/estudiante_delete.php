<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../model/EstudianteModel.php';
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . '/../../../common/model/db.php';

use Serviciosocial\Model\EstudianteModel;
use Serviciosocial\Model\UserModel;
use Common\Model\Database;

// Validar sesión y rol
$user = $_SESSION['user'] ?? null;
$allowedRoles = ['ss_admin'];

if (!is_array($user) || !in_array(strtolower((string)($user['role'] ?? '')), $allowedRoles, true)) {
    header('Location: ../../../common/login.php?module=serviciosocial&error=unauthorized');
    exit;
}

$id = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
} else {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
}

if ($id <= 0) {
    header('Location: estudiante_list.php?error=invalid');
    exit;
}

$message = '';
$estudiante = null;

/** @var \PDO|null $pdo */
$pdo = null;

try {
    $pdo = Database::getConnection();
    $estudianteModel = new EstudianteModel($pdo);
    $userModel = new UserModel($pdo);

    $estudiante = $estudianteModel->findById($id);
    if ($estudiante === null) {
        header('Location: estudiante_list.php?error=notfound');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $pdo->beginTransaction();

            $userModel->deleteByEstudianteId($id);
            $estudianteModel->delete($id);

            $pdo->commit();

            header('Location: estudiante_list.php?deleted=1');
            exit;
        } catch (Throwable $deleteException) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $message = 'No fue posible eliminar al estudiante: ' . $deleteException->getMessage();
        }
    }
} catch (Throwable $exception) {
    if ($pdo instanceof \PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $message = 'Ocurrió un error al cargar la información del estudiante: ' . $exception->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Eliminar estudiante</title>
  <link rel="stylesheet" href="../../assets/serviciosocialstyles.css">
</head>
<body>
  <header>
    <h1>Eliminar estudiante</h1>
    <p>Confirma si deseas eliminar definitivamente al estudiante del sistema.</p>
  </header>

  <main class="page-wrapper">
    <?php if ($message !== ''): ?>
      <div class="alert alert-error" role="alert"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <?php if ($estudiante !== null && $message === ''): ?>
      <section class="form-card">
        <header class="form-card__header">
          <h2>¿Deseas eliminar a este estudiante?</h2>
          <p>Esta acción es permanente y eliminará también las credenciales de acceso vinculadas.</p>
        </header>

        <div class="form-card__body">
          <dl>
            <dt>Nombre</dt>
            <dd><?php echo htmlspecialchars((string)($estudiante['nombre'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></dd>
            <dt>Matrícula</dt>
            <dd><?php echo htmlspecialchars((string)($estudiante['matricula'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></dd>
            <dt>Correo electrónico</dt>
            <dd><?php echo htmlspecialchars((string)($estudiante['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></dd>
          </dl>

          <form method="POST" class="form-actions">
            <input type="hidden" name="id" value="<?php echo (int)$id; ?>">
            <a class="btn-secondary" href="estudiante_list.php">Cancelar</a>
            <button type="submit" class="btn-danger">Eliminar estudiante</button>
          </form>
        </div>
      </section>
    <?php elseif ($estudiante !== null): ?>
      <div class="form-card">
        <p>Por favor regresa a la lista de estudiantes e inténtalo nuevamente.</p>
        <a class="btn-secondary" href="estudiante_list.php">Volver a la lista</a>
      </div>
    <?php endif; ?>
  </main>
</body>
</html>
