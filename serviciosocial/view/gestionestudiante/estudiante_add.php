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
    header('Location: ../../common/login.php?module=serviciosocial&error=unauthorized');
    exit;
}

// Manejar formulario
$message = '';
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
  <main>
    <?php if ($message): ?>
      <div class="alert-error"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label>Nombre completo *</label>
        <input type="text" name="nombre" required>
      </div>
      <div class="form-group">
        <label>Matrícula *</label>
        <input type="text" name="matricula" required>
      </div>
      <div class="form-group">
        <label>Carrera</label>
        <input type="text" name="carrera">
      </div>
      <div class="form-group">
        <label>Correo electrónico *</label>
        <input type="email" name="email" required>
      </div>
      <div class="form-group">
        <label>Teléfono</label>
        <input type="text" name="telefono">
      </div>
      <div class="form-group">
        <label>Contraseña inicial *</label>
        <input type="password" name="password" required>
      </div>
      <button type="submit" class="btn-add">Guardar</button>
    </form>

    <p><a href="estudiante_list.php">← Volver a la lista</a></p>
  </main>
</body>
</html>
