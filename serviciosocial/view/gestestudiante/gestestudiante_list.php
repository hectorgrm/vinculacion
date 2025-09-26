<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../controller/GestEstudianteController.php';

use Serviciosocial\Controller\GestEstudianteController;

$user = $_SESSION['user'] ?? null;
$allowedRoles = ['ss_admin'];

if (!is_array($user) || !in_array(strtolower((string)($user['role'] ?? '')), $allowedRoles, true)) {
    header('Location: ../../../common/login.php?module=serviciosocial&error=unauthorized');
    exit;
}

$searchTerm = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
$error = '';
$students = [];

try {
    $controller = new GestEstudianteController();
    $students = $controller->list($searchTerm);
} catch (\Throwable $exception) {
    $error = 'No fue posible obtener la lista de estudiantes: ' . $exception->getMessage();
}

$formatDate = static function (?string $value): ?string {
    if ($value === null || $value === '') {
        return null;
    }

    $date = date_create($value);
    if ($date === false) {
        return null;
    }

    return $date->format('d/m/Y');
};

$estadoLabels = [
    'pendiente' => 'Pendiente',
    'en_curso'  => 'En curso',
    'concluido' => 'Concluido',
    'cancelado' => 'Cancelado',
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Estudiantes · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/gestestudiante/estudianteliststyles.css">
</head>
<body>
<header>
    <h1>Gestión de Estudiantes · Servicio Social</h1>
</header>

<main>
   <a href="../../index.php" class="btn-back">⬅ Regresar</a>
   
    <div class="search-bar">
        <form method="get" action="">
            <input type="text" name="q" placeholder="Buscar por nombre, matrícula o carrera..." value="<?php echo htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8'); ?>" />
            <button type="submit">Buscar</button>
        </form>
    </div>

    <?php if ($error !== ''): ?>
        <div class="alert alert-error" role="alert"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <div class="top-actions">
        <h2>Lista de estudiantes registrados</h2>
        <a href="../altaestudiante/estudiante_add.php" class="btn new">+ Alta de estudiante</a>
    </div>

    <table>
        <thead>
        <tr>
            <th>Nombre</th>
            <th>Matrícula</th>
            <th>Carrera</th>
            <th>Dependencia / Plaza</th>
            <th>Periodo</th>
            <th>Horas</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($students)): ?>
            <tr>
                <td colspan="8">No hay estudiantes registrados.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($students as $student): ?>
                <?php
                $inicio = $formatDate($student['periodo_inicio'] ?? null);
                $fin = $formatDate($student['periodo_fin'] ?? null);
                $periodo = '-';
                if ($inicio !== null && $fin !== null) {
                    $periodo = $inicio . ' - ' . $fin;
                } elseif ($inicio !== null) {
                    $periodo = $inicio . ' - ?';
                } elseif ($fin !== null) {
                    $periodo = '? - ' . $fin;
                }

                $estado = strtolower((string)($student['estado_servicio'] ?? ''));
                $estadoLabel = $estadoLabels[$estado] ?? 'Desconocido';
                ?>
                <tr>
                    <td><?php echo htmlspecialchars((string) $student['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars((string) $student['matricula'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars((string)($student['carrera'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars((string)($student['plaza_nombre'] ?? $student['dependencia_asignada'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($periodo, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo (int)($student['horas_acumuladas'] ?? 0); ?> / <?php echo (int)($student['horas_requeridas'] ?? 0); ?></td>
                    <td><span class="status <?php echo htmlspecialchars($estado, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($estadoLabel, ENT_QUOTES, 'UTF-8'); ?></span></td>
                    <td class="actions">
                        <a href="gestestudiente_view.php?id=<?php echo (int) $student['id']; ?>" class="btn view">Ver</a>
                        <a href="gestestudiante_edit.php?id=<?php echo (int) $student['id']; ?>" class="btn edit">Editar</a>
                        <a href="gestestudiante_delete.php?id=<?php echo (int) $student['id']; ?>" class="btn delete">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</main>
</body>
</html>
