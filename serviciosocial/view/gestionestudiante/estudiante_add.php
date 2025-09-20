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

// Manejar formulario
$message = '';
$nombre = '';
$matricula = '';
$carrera = '';
$email = '';
$telefono = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = trim($_POST['nombre'] ?? '');
    $matricula = trim($_POST['matricula'] ?? '');
    $carrera   = trim($_POST['carrera'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $telefono  = trim($_POST['telefono'] ?? '');
    $password  = trim($_POST['password'] ?? '');

    if ($nombre !== '' && $matricula !== '' && $email !== '' && $password !== '') {
        try {
            $pdo = Database::getConnection();
            $estudianteModel = new EstudianteModel($pdo);
            $userModel = new UserModel($pdo);

            // Insertar estudiante
            $estudianteId = $estudianteModel->create([
                'nombre'    => $nombre,
                'matricula' => $matricula,
                'carrera'   => $carrera,
                'email'     => $email,
                'telefono'  => $telefono
            ]);

            // Crear usuario vinculado
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            $userModel->create([
                'nombre'        => $nombre,
                'email'         => $email,
                'password_hash' => $passwordHash,
                'rol'           => 'estudiante',
                'estudiante_id' => $estudianteId
            ]);

            header('Location: estudiante_list.php?success=1');
            exit;
        } catch (Throwable $e) {
            $message = "Error al dar de alta: " . $e->getMessage();
        }
    } else {
        $message = "Por favor completa los campos obligatorios.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dar de Alta Estudiante</title>
  <link rel="stylesheet" href="../../assets/serviciosocialstyles.css">
</head>
<body>
  <header>
    <h1>Dar de Alta Estudiante</h1>
  </header>
  <main class="page-wrapper">
    <?php if ($message): ?>
      <div class="alert alert-error" role="alert"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <section class="form-card">
      <header class="form-card__header">
        <h2>Información del Estudiante</h2>
        <p>Completa los datos para dar de alta al nuevo estudiante dentro del sistema.</p>
      </header>

      <form method="POST" class="form-card__body">
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
            <label for="telefono">Teléfono</label>
            <input id="telefono" class="form-control" type="tel" name="telefono" value="<?php echo htmlspecialchars($telefono, ENT_QUOTES, 'UTF-8'); ?>" placeholder="10 dígitos">
          </div>
          <div class="form-group">
            <label for="password">Contraseña inicial *</label>
            <input id="password" class="form-control" type="password" name="password" required placeholder="Mínimo 8 caracteres">
          </div>
        </div>

        <div class="form-actions">
          <a class="btn-secondary" href="estudiante_list.php">Cancelar</a>
          <button type="submit" class="btn-add">Guardar estudiante</button>
        </div>
      </form>
    </section>
  </main>
</body>
</html>
