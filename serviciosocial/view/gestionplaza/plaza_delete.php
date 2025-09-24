<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../controller/PlazaController.php';

use Serviciosocial\Controller\PlazaController;

$user = $_SESSION['user'] ?? null;
$allowedRoles = ['ss_admin'];

if (!is_array($user) || !in_array(strtolower((string)($user['role'] ?? '')), $allowedRoles, true)) {
    header('Location: ../../../common/login.php?module=serviciosocial&error=unauthorized');
    exit;
}

$controller = new PlazaController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
} else {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
}

if ($id <= 0) {
    header('Location: plaza_list.php?error=invalid');
    exit;
}

$message = '';
$plaza = null;

try {
    $plaza = $controller->findById($id);
    if ($plaza === null) {
        header('Location: plaza_list.php?error=notfound');
        exit;
    }
} catch (\Throwable $exception) {
    $message = 'Ocurrió un error al cargar la información de la plaza: ' . $exception->getMessage();
}

if ($message === '' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $controller->delete($id);
        header('Location: plaza_list.php?deleted=1');
        exit;
    } catch (\Throwable $deleteException) {
        $message = 'No fue posible eliminar la plaza: ' . $deleteException->getMessage();
    }
}

$formatDate = static function (?string $value): string {
    if ($value === null) {
        return '-';
    }

    $value = trim($value);
    if ($value === '') {
        return '-';
    }

    $date = date_create($value);
    if ($date === false) {
        return $value;
    }

    return $date->format('d/m/Y');
};

$formatModalidad = static function (?string $value): string {
    $value = strtolower(trim((string) $value));
    return match ($value) {
        'presencial' => 'Presencial',
        'hibrida' => 'Híbrida',
        'remota' => 'Remota',
        default => '-',
    };
};

$formatEstado = static function (?string $value): string {
    $value = strtolower(trim((string) $value));
    return $value === 'activa' ? 'Activa' : ($value === 'inactiva' ? 'Inactiva' : '-');
};
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Plaza</title>
    <link rel="stylesheet" href="../../assets/serviciosocialstyles.css">
</head>
<body>
    <header>
        <h1>Eliminar plaza</h1>
        <p>Confirma si deseas eliminar definitivamente la plaza del catálogo de Servicio Social.</p>
    </header>

    <main class="page-wrapper">
        <?php if ($message !== ''): ?>
            <div class="alert alert-error" role="alert"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <?php if ($plaza !== null && $message === ''): ?>
            <section class="form-card">
                <header class="form-card__header">
                    <h2>¿Deseas eliminar esta plaza?</h2>
                    <p>Esta acción es permanente y no se puede deshacer.</p>
                </header>

                <div class="form-card__body">
                    <dl>
                        <dt>Nombre</dt>
                        <dd><?php echo htmlspecialchars((string)($plaza['nombre'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></dd>

                        <dt>Dependencia / Empresa</dt>
                        <dd><?php echo htmlspecialchars((string)($plaza['empresa_nombre'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></dd>

                        <dt>Modalidad</dt>
                        <dd><?php echo htmlspecialchars($formatModalidad($plaza['modalidad'] ?? null), ENT_QUOTES, 'UTF-8'); ?></dd>

                        <dt>Cupo</dt>
                        <dd><?php echo isset($plaza['cupo']) ? (int) $plaza['cupo'] : '-'; ?></dd>

                        <dt>Periodo</dt>
                        <dd>
                            <?php echo htmlspecialchars($formatDate($plaza['periodo_inicio'] ?? null), ENT_QUOTES, 'UTF-8'); ?>
                            -
                            <?php echo htmlspecialchars($formatDate($plaza['periodo_fin'] ?? null), ENT_QUOTES, 'UTF-8'); ?>
                        </dd>

                        <dt>Estado</dt>
                        <dd><?php echo htmlspecialchars($formatEstado($plaza['estado'] ?? null), ENT_QUOTES, 'UTF-8'); ?></dd>
                    </dl>

                    <form method="post" class="form-actions">
                        <input type="hidden" name="id" value="<?php echo (int) $id; ?>">
                        <a class="btn-secondary" href="plaza_list.php">Cancelar</a>
                        <button type="submit" class="btn-danger">Eliminar plaza</button>
                    </form>
                </div>
            </section>
        <?php elseif ($plaza !== null): ?>
            <div class="form-card">
                <p>Por favor regresa a la lista de plazas e inténtalo nuevamente.</p>
                <a class="btn-secondary" href="plaza_list.php">Volver a la lista</a>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
