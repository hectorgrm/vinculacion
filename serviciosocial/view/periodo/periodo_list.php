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

$searchTerm = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
$error = '';
$periodos = [];

try {
    $controller = new PeriodoController();
    $periodos = $controller->list($searchTerm);
} catch (Throwable $exception) {
    $error = 'No fue posible obtener la lista de periodos: ' . $exception->getMessage();
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
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Periodos · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/periodo/periodolist.css"/>
</head>
<body>

<header>
  <h1>Gestión de Periodos</h1>
  <nav class="breadcrumb">
    <a href="../../index.php">Inicio</a>
    <span>›</span>
    <span>Periodos</span>
  </nav>
</header>

<main>
  <div class="top-actions">
    <a href="../../index.php" class="btn-back">⬅ Volver</a>
    <a href="#" class="btn-new">➕ Nuevo Periodo</a>
  </div>

  <div class="search-bar">
    <form action="" method="get">
      <input type="text" name="q" placeholder="Buscar por servicio, número o estado..." value="<?php echo htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8'); ?>" />
      <button type="submit">Buscar</button>
    </form>
  </div>

  <?php if ($error !== ''): ?>
    <div class="alert alert-error" role="alert"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
  <?php endif; ?>

  <table class="styled-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Servicio ID</th>
        <th>Número</th>
        <th>Estatus</th>
        <th>Apertura</th>
        <th>Cierre</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($periodos)): ?>
        <tr>
          <td colspan="7">No hay periodos registrados.</td>
        </tr>
      <?php else: ?>
        <?php foreach ($periodos as $periodo): ?>
          <?php
          $estatus = strtolower((string)($periodo['estatus'] ?? ''));
          $estatusLabel = $statusLabels[$estatus] ?? ucfirst($estatus);
          ?>
          <tr>
            <td><?php echo (int) ($periodo['id'] ?? 0); ?></td>
            <td><?php echo htmlspecialchars((string)($periodo['servicio_id'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars((string)($periodo['numero'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
            <td><span class="status <?php echo htmlspecialchars($estatus, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($estatusLabel, ENT_QUOTES, 'UTF-8'); ?></span></td>
            <td><?php echo htmlspecialchars($formatDate($periodo['abierto_en'] ?? null), ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($formatDate($periodo['cerrado_en'] ?? null), ENT_QUOTES, 'UTF-8'); ?></td>
            <td class="actions">
              <a href="#" class="btn-view">Ver</a>
              <a href="#" class="btn-edit">Editar</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</main>

</body>
</html>
