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

$error = '';
$success = '';
$plazas = [];

if (isset($_GET['created'])) {
    $success = 'La plaza se registró correctamente.';
} elseif (isset($_GET['updated'])) {
    $success = 'La plaza se actualizó correctamente.';
} elseif (isset($_GET['deleted'])) {
    $success = 'La plaza se eliminó correctamente.';
}

try {
    $controller = new PlazaController();
    $plazas = $controller->listAll();
} catch (\Throwable $exception) {
    $error = 'No fue posible obtener la lista de plazas: ' . $exception->getMessage();
}

if ($error === '' && isset($_GET['error'])) {
    if ($_GET['error'] === 'invalid') {
        $error = 'La solicitud para editar la plaza no es válida.';
    } elseif ($_GET['error'] === 'notfound') {
        $error = 'La plaza solicitada no se encontró en el sistema.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Plazas</title>
    <link rel="stylesheet" href="../../assets/serviciosocialstyles.css">
</head>
<body>
    <header>
        <h1>Gestión de Plazas</h1>
        <p>Aquí puedes administrar las plazas de Servicio Social</p>
    </header>

    <main>
        <?php if ($error !== ''): ?>
            <div class="alert alert-error" role="alert"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php elseif ($success !== ''): ?>
            <div class="alert alert-success" role="status"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <div class="top-actions">
            <a href="../../index.php" class="btn-back">⬅ Regresar</a>
            <a href="plaza_add.php" class="btn-add">➕ Dar de Alta Plaza</a>
        </div>

        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Empresa</th>
                    <th>Dirección</th>
                    <th>Cupo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($plazas)): ?>
                    <?php foreach ($plazas as $plaza): ?>
                        <tr>
                            <td><?php echo (int)$plaza['id']; ?></td>
                            <td><?php echo htmlspecialchars($plaza['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($plaza['empresa_nombre'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($plaza['direccion'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo $plaza['cupo'] !== null ? (int)$plaza['cupo'] : '-'; ?></td>
                            <td>
                                <?php
                                $estado = strtolower((string)($plaza['estado'] ?? ''));
                                echo $estado === 'activa' ? 'Activa' : ($estado === 'inactiva' ? 'Inactiva' : '-');
                                ?>
                            </td>
                            <td class="actions">
                            <a href="plaza_view.php?id=<?php echo (int)$plaza['id']; ?>"> ver </a>
                            <a href="plaza_edit.php?id=<?php echo (int)$plaza['id']; ?>"> Editar</a>
                            <a href="plaza_delete.php?id=<?php echo (int)$plaza['id']; ?>"> Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No hay plazas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
