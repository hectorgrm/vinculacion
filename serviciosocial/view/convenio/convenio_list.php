<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../controller/ConvenioController.php';

use Serviciosocial\Controller\ConvenioController;

$search = isset($_GET['search']) ? trim((string) $_GET['search']) : '';
$convenios = [];
$error = '';

try {
    $controller = new ConvenioController();
    $convenios = $controller->listAll($search === '' ? null : $search);
} catch (\Throwable $exception) {
    $error = 'No fue posible obtener los convenios registrados: ' . $exception->getMessage();
}

/**
 * @param string $value
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$estatusConfig = [
    'pendiente' => ['label' => 'Pendiente', 'class' => 'pendiente'],
    'vigente' => ['label' => 'Vigente', 'class' => 'vigente'],
    'vencido' => ['label' => 'Vencido', 'class' => 'vencido'],
];

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Convenios - Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/convenios/conveniostyles.css" />
</head>
<body>

  <header>
    <h1>Gestión de Convenios</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <span>Convenios</span>
    </nav>
  </header>

  <main>
    <section class="card">
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Listado de Convenios</h2>
        <a href="convenio_add.php" class="btn btn-primary">➕ Nuevo Convenio</a>
      </div>

      <?php if ($error !== ''): ?>
        <div class="alert alert-error" role="alert" style="margin-top: 20px;">
          <?php echo e($error); ?>
        </div>
      <?php endif; ?>

      <form action="" method="get" style="margin: 20px 0; display: flex; gap: 10px;">
        <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="Buscar por empresa o estado..." style="flex: 1;" />
        <button type="submit" class="btn btn-primary">Buscar</button>
        <a href="convenio_list.php" class="btn btn-secondary">Restablecer</a>
      </form>

      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Empresa</th>
              <th>Estatus</th>
              <th>Fecha de Inicio</th>
              <th>Fecha de Fin</th>
              <th>Versión</th>
              <th>Registrado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($convenios)): ?>
              <tr>
                <td colspan="8">
                  <div class="empty-state">
                    No hay convenios registrados<?php echo $search !== '' ? ' que coincidan con la búsqueda.' : '.'; ?>
                  </div>
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($convenios as $convenio): ?>
                <?php
                $estatus = strtolower((string) ($convenio['estatus'] ?? ''));
                $estatusInfo = $estatusConfig[$estatus] ?? ['label' => ucfirst($estatus), 'class' => ''];
                ?>
                <tr>
                  <td><?php echo (int) ($convenio['id'] ?? 0); ?></td>
                  <td><?php echo e((string) ($convenio['empresa_nombre'] ?? '')); ?></td>
                  <td>
                    <span class="status <?php echo e($estatusInfo['class']); ?>">
                      <?php echo e($estatusInfo['label']); ?>
                    </span>
                  </td>
                  <td><?php echo e((string) ($convenio['fecha_inicio'] ?? '-')); ?></td>
                  <td><?php echo e((string) ($convenio['fecha_fin'] ?? '-')); ?></td>
                  <td><?php echo e((string) ($convenio['version_actual'] ?? '-')); ?></td>
                  <td><?php echo e((string) ($convenio['creado_en'] ?? '-')); ?></td>
                  <td>
                    <a href="convenio_view.php?id=<?php echo (int) ($convenio['id'] ?? 0); ?>" class="btn btn-secondary">🔍 Ver</a>
                    <a href="convenio_edit.php?id=<?php echo (int) ($convenio['id'] ?? 0); ?>" class="btn btn-primary">✏️ Editar</a>
                    <a href="convenio_delete.php?id=<?php echo (int) ($convenio['id'] ?? 0); ?>" class="btn btn-danger">🗑️ Eliminar</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>

</body>
</html>
