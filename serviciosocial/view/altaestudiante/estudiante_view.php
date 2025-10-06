<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../model/EstudianteModel.php';
require_once __DIR__ . '/../../../common/model/db.php';

use Serviciosocial\Model\EstudianteModel;
use Common\Model\Database;

$user = $_SESSION['user'] ?? null;
$allowedRoles = ['ss_admin'];

if (!is_array($user) || !in_array(strtolower((string)($user['role'] ?? '')), $allowedRoles, true)) {
    header('Location: ../../../common/login.php?module=serviciosocial&error=unauthorized');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: estudiante_list.php?error=invalid');
    exit;
}

try {
    $pdo = Database::getConnection();
    $model = new EstudianteModel($pdo);
    $estudiante = $model->findById($id);
} catch (Throwable $exception) {
    $estudiante = null;
}

if ($estudiante === null) {
    header('Location: estudiante_list.php?error=notfound');
    exit;
}

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function escapeOrDefault(?string $value, string $default = 'No registrado'): string
{
    $value = $value !== null ? trim($value) : '';

    return $value !== '' ? escape($value) : $default;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del estudiante · <?php echo escape((string)($estudiante['nombre'] ?? '')); ?></title>
    <link rel="stylesheet" href="../../assets/css/gestestudiante/estudiantealtaviewstyles.css">
</head>
<body>
<header>
    <div class="header-content">
        <div>
            <h1>Detalle del estudiante</h1>
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="../../index.php">Inicio</a>
                <span aria-hidden="true">›</span>
                <a href="estudiante_list.php">Gestión de estudiantes</a>
                <span aria-hidden="true">›</span>
                <span aria-current="page">Detalle</span>
            </nav>
        </div>
        <p class="user-greeting">Hola, <?php echo escape((string)($user['name'] ?? '')); ?></p>
    </div>
</header>

<main>
    <section class="card">
        <h2>Información general</h2>
        <div class="grid">
            <div class="field">
                <span class="label">Nombre completo</span>
                <p><?php echo escapeOrDefault($estudiante['nombre'] ?? null); ?></p>
            </div>
            <div class="field">
                <span class="label">Matrícula</span>
                <p><?php echo escapeOrDefault($estudiante['matricula'] ?? null); ?></p>
            </div>
            <div class="field">
                <span class="label">Programa educativo</span>
                <p><?php echo escapeOrDefault($estudiante['carrera'] ?? null); ?></p>
            </div>
        </div>
    </section>

    <section class="card">
        <h2>Contacto</h2>
        <div class="grid">
            <div class="field">
                <span class="label">Correo electrónico</span>
                <p><?php echo escapeOrDefault($estudiante['email'] ?? null); ?></p>
            </div>
            <div class="field">
                <span class="label">Teléfono</span>
                <p><?php echo escapeOrDefault($estudiante['telefono'] ?? null); ?></p>
            </div>
        </div>
    </section>

    <div class="actions">
        <a class="btn ghost" href="estudiante_list.php">Volver</a>
        <a class="btn primary" href="estudiante_edit.php?id=<?php echo (int)$estudiante['id']; ?>">Editar</a>
    </div>
</main>

<footer>
    &copy; <?php echo date('Y'); ?> Servicio Social - Instituto Tecnológico de Villahermosa
</footer>
</body>
</html>
