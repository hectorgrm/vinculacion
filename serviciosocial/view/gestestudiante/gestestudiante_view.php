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

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: gestestudiante_list.php?error=invalid');
    exit;
}

$controller = new GestEstudianteController();
$student = $controller->find($id);

if ($student === null) {
    header('Location: gestestudiante_list.php?error=notfound');
    exit;
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

$estado = strtolower((string)($student['estado_servicio'] ?? 'pendiente'));
$estadoLabel = $estadoLabels[$estado] ?? 'Pendiente';
$periodoInicio = $formatDate($student['periodo_inicio'] ?? null);
$periodoFin = $formatDate($student['periodo_fin'] ?? null);
$periodo = 'Sin periodo registrado';

if ($periodoInicio !== null && $periodoFin !== null) {
    $periodo = $periodoInicio . ' - ' . $periodoFin;
} elseif ($periodoInicio !== null) {
    $periodo = $periodoInicio . ' - ?';
} elseif ($periodoFin !== null) {
    $periodo = '? - ' . $periodoFin;
}

$documentos = trim((string)($student['documentos_entregados'] ?? ''));
$observaciones = trim((string)($student['observaciones'] ?? ''));

function escapeWithBreaks(string $value): string
{
    $safe = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    return nl2br($safe, false);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del estudiante · <?php echo htmlspecialchars((string)($student['nombre'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="../../assets/css/gestestudiante/estudianteviewstyles.css">
</head>
<body>

<header>
    <h1>Servicio Social · Detalle del estudiante</h1>
    <nav class="breadcrumb">
        <a href="../../index.php">Inicio</a>
        <span class="sep">›</span>
        <a href="gestestudiante_list.php">Gestión de Estudiantes</a>
        <span class="sep">›</span>
        <span>Detalle</span>
    </nav>
</header>

<main>

    <div class="section">
        <h2>Información personal</h2>
        <div class="grid">
            <div class="field">
                <label>Nombre completo</label>
                <p><?php echo htmlspecialchars((string)($student['nombre'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <div class="field">
                <label>Matrícula</label>
                <p><?php echo htmlspecialchars((string)($student['matricula'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <div class="field">
                <label>Programa académico</label>
                <p><?php echo htmlspecialchars((string)($student['carrera'] ?? 'No registrado'), ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <div class="field">
                <label>Semestre</label>
                <p><?php echo htmlspecialchars($student['semestre'] !== null ? (string) $student['semestre'] : 'No registrado', ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <div class="field">
                <label>Correo electrónico</label>
                <p><?php echo htmlspecialchars((string)($student['email'] ?? 'No registrado'), ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <div class="field">
                <label>Teléfono</label>
                <p><?php echo htmlspecialchars((string)($student['telefono'] ?? 'No registrado'), ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Información del servicio social</h2>
        <div class="grid">
            <div class="field">
                <label>Plaza / Dependencia</label>
                <p><?php echo htmlspecialchars((string)($student['plaza_nombre'] ?? $student['dependencia_asignada'] ?? 'No asignada'), ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <div class="field">
                <label>Proyecto</label>
                <p><?php echo htmlspecialchars((string)($student['proyecto'] ?? 'No registrado'), ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <div class="field">
                <label>Periodo</label>
                <p><?php echo htmlspecialchars($periodo, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <div class="field">
                <label>Horas acumuladas</label>
                <p><?php echo (int)($student['horas_acumuladas'] ?? 0); ?> / <?php echo (int)($student['horas_requeridas'] ?? 0); ?> horas</p>
            </div>
            <div class="field">
                <label>Estado del servicio</label>
                <p><?php echo htmlspecialchars($estadoLabel, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <div class="field">
                <label>Asesor interno</label>
                <p><?php echo htmlspecialchars((string)($student['asesor_interno'] ?? 'No registrado'), ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Documentación</h2>
        <div class="field">
            <label>Documentos entregados</label>
            <p><?php echo $documentos !== '' ? escapeWithBreaks($documentos) : 'Sin documentación registrada.'; ?></p>
        </div>
        <div class="field">
            <label>Observaciones</label>
            <p><?php echo $observaciones !== '' ? escapeWithBreaks($observaciones) : 'Sin observaciones.'; ?></p>
        </div>
    </div>

    <div class="actions">
        <a href="gestestudiante_list.php" class="btn ghost">Volver</a>
        <a href="gestestudiante_edit.php?id=<?php echo (int) $student['id']; ?>" class="btn primary">Editar</a>
    </div>

</main>

</body>
</html>
