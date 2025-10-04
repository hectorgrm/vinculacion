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
$nombre = '';
$matricula = '';
$carrera = '';
$email = '';
$telefono = '';

/** @var \PDO|null $pdo */
$pdo = null;
$estudiante = null;

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
        $nombre = trim($_POST['nombre'] ?? '');
        $matricula = trim($_POST['matricula'] ?? '');
        $carrera = trim($_POST['carrera'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');

        $passwordInput = $_POST['password'] ?? '';
        $password = is_string($passwordInput) ? trim($passwordInput) : '';

        if ($nombre !== '' && $matricula !== '' && $email !== '') {
            try {
                $pdo->beginTransaction();

                $estudianteModel->update($id, [
                    'nombre'    => $nombre,
                    'matricula' => $matricula,
                    'carrera'   => $carrera,
                    'email'     => $email,
                    'telefono'  => $telefono,
                ]);

                $userData = [
                    'nombre' => $nombre,
                    'email'  => $email,
                ];

                if ($password !== '') {
                    $userData['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
                }

                $userModel->updateByEstudianteId($id, $userData);

                $pdo->commit();

                header('Location: estudiante_list.php?updated=1');
                exit;
            } catch (Throwable $updateException) {
                if ($pdo instanceof \PDO && $pdo->inTransaction()) {
                    $pdo->rollBack();
                }

                $message = 'No fue posible actualizar la información: ' . $updateException->getMessage();
            }
        } else {
            $message = 'Por favor completa los campos obligatorios marcados con *.';
        }
    } else {
        $nombre = (string)($estudiante['nombre'] ?? '');
        $matricula = (string)($estudiante['matricula'] ?? '');
        $carrera = (string)($estudiante['carrera'] ?? '');
        $email = (string)($estudiante['email'] ?? '');
        $telefono = (string)($estudiante['telefono'] ?? '');
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
  <title>Editar Estudiante</title>
  <link rel="stylesheet" href="../../assets/css/gestestudiante/estudianteeditstlyes.css">
</head>
<body>
  <header>
    <h1>Editar información del estudiante</h1>
    <p>Modifica los datos del perfil y confirma para guardar los cambios.</p>
  </header>

  <main class="page-wrapper">
    <?php if ($message !== ''): ?>
      <div class="alert alert-error" role="alert"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <?php if ($estudiante !== null): ?>
      <section class="form-card">
        <header class="form-card__header">
          <h2>Detalles generales</h2>
          <p>Revisa cuidadosamente los datos antes de actualizar al estudiante.</p>
        </header>

        <form method="POST" class="form-card__body" novalidate>
          <input type="hidden" name="id" value="<?php echo (int)$id; ?>">
          <div class="form-grid">
            <div class="form-group">
              <label for="nombre">Nombre completo *</label>
              <input id="nombre" class="form-control" type="text" name="nombre" value="<?php echo htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?>" required placeholder="Nombre(s) y apellidos">
            </div>
            <div class="form-group">
              <label for="matricula">Matrícula *</label>
              <input id="matricula" class="form-control" type="text" name="matricula" value="<?php echo htmlspecialchars($matricula, ENT_QUOTES, 'UTF-8'); ?>" required placeholder="Ej. A01234567">
            </div>
            <div class="form-group">
              <label for="carrera">Carrera</label>
              <input id="carrera" class="form-control" type="text" name="carrera" value="<?php echo htmlspecialchars($carrera, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Programa académico">
            </div>
            <div class="form-group">
              <label for="email">Correo electrónico *</label>
              <input id="email" class="form-control" type="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" required placeholder="correo@ejemplo.com">
            </div>
            <div class="form-group">
              <label for="password">Contraseña (opcional)</label>
              <input id="password" class="form-control" type="password" name="password" placeholder="Dejar vacío si no desea cambiar">
            </div>
            <div class="form-group">
              <label for="telefono">Teléfono</label>
              <input id="telefono" class="form-control" type="tel" name="telefono" value="<?php echo htmlspecialchars($telefono, ENT_QUOTES, 'UTF-8'); ?>" placeholder="10 dígitos">
            </div>
          </div>

          <div class="form-actions">
            <a class="btn-secondary" href="estudiante_list.php">Cancelar</a>
            <button type="submit" class="btn-add">Actualizar estudiante</button>
          </div>
        </form>
      </section>
    <?php endif; ?>
  </main>
</body>
</html>
