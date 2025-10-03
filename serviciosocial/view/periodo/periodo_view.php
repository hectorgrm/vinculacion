<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../controller/PeriodoController.php';

use Serviciosocial\Controller\PeriodoController;

$user = $_SESSION['user'] ?? null;
$allowedRoles = ['ss_admin', 'admin_ss'];

if (!is_array($user) || !in_array(strtolower((string)($user['role'] ?? '')), $allowedRoles, true)) {
    header('Location: ../../../common/login.php?module=serviciosocial&error=unauthorized');
    exit;
}

$idParam = $_GET['id'] ?? null;
$periodoId = is_numeric($idParam) ? (int) $idParam : 0;

$error = '';
$periodo = null;

try {
    if ($periodoId <= 0) {
        throw new \RuntimeException('Identificador de periodo inválido.');
    }

    $controller = new PeriodoController();
    $periodo = $controller->find($periodoId);

    if ($periodo === null) {
        throw new \RuntimeException('El periodo solicitado no existe.');
    }
} catch (\Throwable $exception) {
    $error = $exception->getMessage();
}

$formatDate = static function (?string $value): string {
    if ($value === null || $value === '') {
        return '-';
    }

    $date = date_create($value);
    if ($date === false) {
        return '-';
    }

    return $date->format('d/m/Y H:i');
};

$statusLabels = [
    'abierto'      => 'Abierto',
    'en_revision'  => 'En revisión',
    'completado'   => 'Completado',
];

$estatus = '';
$estatusLabel = '';

if ($periodo !== null) {
    $estatus = strtolower((string)($periodo['estatus'] ?? ''));
    $estatusLabel = $statusLabels[$estatus] ?? ucfirst($estatus);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalle del Periodo · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/periodo/periodoview.css">
</head>
<body>

  <header>
    <h1>Detalle del Periodo</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="periodo_list.php">Gestión de Periodos</a>
      <span>›</span>
      <span>Detalle</span>
    </nav>
  </header>

  <main>
    <?php if ($error !== ''): ?>
      <div class="alert alert-error" role="alert">
        <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php else: ?>
      <div class="card">
        <h2>Información del periodo</h2>

        <div class="grid">
          <div class="field">
            <label>ID del periodo</label>
            <p><?php echo (int) ($periodo['id'] ?? 0); ?></p>
          </div>
          <div class="field">
            <label>Servicio asociado</label>
            <p><?php echo htmlspecialchars((string)($periodo['servicio_id'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
          <div class="field">
            <label>Número de periodo</label>
            <p><?php echo htmlspecialchars((string)($periodo['numero'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
          <div class="field">
            <label>Estatus actual</label>
            <span class="status <?php echo htmlspecialchars($estatus, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($estatusLabel, ENT_QUOTES, 'UTF-8'); ?></span>
          </div>
          <div class="field">
            <label>Fecha de apertura</label>
            <p><?php echo htmlspecialchars($formatDate($periodo['abierto_en'] ?? null), ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
          <div class="field">
            <label>Fecha de cierre</label>
            <p><?php echo htmlspecialchars($formatDate($periodo['cerrado_en'] ?? null), ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
        </div>

        <div class="actions">
          <a href="periodo_list.php" class="btn-secondary">Volver al listado</a>
          <a href="periodo_edit.php?id=<?php echo rawurlencode((string) $periodoId); ?>" class="btn-primary">Editar periodo</a>
        </div>
      </div>
    <?php endif; ?>
  </main>

</body>
</html>
