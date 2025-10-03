<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../controller/ServicioController.php';

use Serviciosocial\Controller\ServicioController;

$searchTerm = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
$error = '';
$servicios = [];

try {
    $controller = new ServicioController();
    $servicios = $controller->listServicios($searchTerm);
} catch (\Throwable $exception) {
    $error = 'No fue posible obtener la lista de servicios: ' . $exception->getMessage();
}

$statusConfig = [
    'prealta'  => ['label' => 'Pre-alta', 'class' => 'prealta'],
    'activo'   => ['label' => 'Activo', 'class' => 'activo'],
    'concluido'=> ['label' => 'Concluido', 'class' => 'concluido'],
    'cancelado'=> ['label' => 'Cancelado', 'class' => 'cancelado'],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Servicios · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/servicio/globalservicio.css" />
</head>
<body>

  <header>
    <h1>Gestión de Servicios · Servicio Social</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <span>Gestión de Servicios</span>
    </nav>
  </header>

  <main>
    <a href="../../index.php" class="btn btn-secondary">⬅ Regresar</a>

    <?php if ($error !== ''): ?>
      <div class="alert alert-error" role="alert">
        <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php endif; ?>

    <!-- 🔍 Barra de búsqueda -->
    <div class="search-bar">
      <form method="get" action="">
        <input
          type="text"
          name="q"
          placeholder="Buscar por nombre, matrícula o plaza..."
          value="<?php echo htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8'); ?>"
        />
        <button type="submit" class="btn btn-primary">Buscar</button>
      </form>
    </div>

    <!-- ✅ Acciones superiores -->
    <div class="top-actions">
      <h2>Servicios registrados</h2>
      <a href="servicio_add.php" class="btn btn-primary">+ Nuevo Servicio</a>
    </div>

    <!-- 📋 Tabla de servicios -->
    <table class="styled-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Estudiante</th>
          <th>Matrícula</th>
          <th>Plaza</th>
          <th>Estado</th>
          <th>Horas</th>
          <th>Creado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($error === '' && !empty($servicios)): ?>
          <?php foreach ($servicios as $servicio): ?>
            <tr>
              <td><?php echo (int) $servicio['id']; ?></td>
              <td><?php echo htmlspecialchars($servicio['estudiante_nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($servicio['estudiante_matricula'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($servicio['plaza_nombre'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
              <td>
                <?php
                $statusKey = strtolower((string) ($servicio['estatus'] ?? ''));
                $status = $statusConfig[$statusKey] ?? ['label' => ucfirst($statusKey), 'class' => ''];
                ?>
                <span class="status <?php echo htmlspecialchars($status['class'], ENT_QUOTES, 'UTF-8'); ?>">
                  <?php echo htmlspecialchars($status['label'], ENT_QUOTES, 'UTF-8'); ?>
                </span>
              </td>
              <td><?php echo (int) $servicio['horas_acumuladas']; ?></td>
              <td>
                <?php
                $createdAt = $servicio['creado_en'] ?? null;
                if ($createdAt !== null) {
                    $date = date_create($createdAt);
                    echo $date instanceof DateTimeInterface
                        ? htmlspecialchars($date->format('Y-m-d'), ENT_QUOTES, 'UTF-8')
                        : htmlspecialchars((string) $createdAt, ENT_QUOTES, 'UTF-8');
                } else {
                    echo '-';
                }
                ?>
              </td>
              <td class="actions">
                <a href="servicio_view.php?id=<?php echo (int) $servicio['id']; ?>" class="btn btn-info">Ver</a>
                <a href="servicio_edit.php?id=<?php echo (int) $servicio['id']; ?>" class="btn btn-warning">Editar</a>
                <a href="servicio_close.php?id=<?php echo (int) $servicio['id']; ?>" class="btn btn-danger">Cerrar</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="8">
              <?php echo $error !== '' ? 'No se pudo cargar la información.' : 'No hay servicios registrados.'; ?>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </main>

</body>
</html>
