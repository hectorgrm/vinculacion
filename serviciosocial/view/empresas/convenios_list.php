<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../controller/EmpresaController.php';

use Serviciosocial\Controller\EmpresaController;

$empresaId = isset($_GET['empresa_id']) ? (int) $_GET['empresa_id'] : 0;
if ($empresaId <= 0) {
    header('Location: empresa_list.php?error=invalid');
    exit;
}

$controller = null;
$empresa = null;
$convenios = [];
$error = '';

try {
    $controller = new EmpresaController();
} catch (\Throwable $exception) {
    $error = 'No fue posible preparar la consulta: ' . $exception->getMessage();
}

if ($controller instanceof EmpresaController && $error === '') {
    try {
        $empresa = $controller->findEmpresa($empresaId);
        if ($empresa === null) {
            header('Location: empresa_list.php?error=notfound');
            exit;
        }

        $convenios = $controller->listConveniosPorEmpresa($empresaId);
    } catch (\Throwable $exception) {
        $error = 'No fue posible obtener los convenios asociados: ' . $exception->getMessage();
    }
}

/**
 * @param string $value
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$estatusConfig = [
    'pendiente' => ['label' => 'Pendiente', 'class' => 'badge-pendiente'],
    'vigente' => ['label' => 'Vigente', 'class' => 'badge-vigente'],
    'vencido' => ['label' => 'Vencido', 'class' => 'badge-vencido'],
];

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Convenios por Empresa · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/empresas/empresaglobalstyles.css" />
</head>
<body>

  <header>
    <h1>Convenios de la Empresa</h1>
    <p>Consulta los convenios registrados para la empresa seleccionada.</p>
  </header>

  <div class="container">
    <?php if ($error !== ''): ?>
      <div class="alert alert-error" role="alert">
        <?php echo e($error); ?>
      </div>
    <?php endif; ?>

    <?php if ($empresa !== null): ?>
      <section class="summary-card">
        <h2><?php echo e((string) ($empresa['nombre'] ?? 'Empresa sin nombre')); ?></h2>
        <p>
          Puedes administrar los convenios desde esta página. Para eliminar la empresa debes eliminar
          previamente todos los convenios asociados.
        </p>
        <div class="summary-actions">
          <a href="empresa_delete.php?id=<?php echo (int) $empresaId; ?>" class="btn btn-danger btn-sm">Regresar a eliminar empresa</a>
          <a href="empresa_list.php" class="btn btn-secondary btn-sm">Volver a la lista de empresas</a>
        </div>
      </section>

      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Estatus</th>
              <th>Fecha de inicio</th>
              <th>Fecha de fin</th>
              <th>Versión actual</th>
              <th>Registrado en</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($convenios)): ?>
              <tr>
                <td colspan="6">
                  <div class="empty-state">
                    No hay convenios registrados para esta empresa.
                  </div>
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($convenios as $convenio): ?>
                <tr>
                  <td data-label="ID"><?php echo (int) ($convenio['id'] ?? 0); ?></td>
                  <td data-label="Estatus">
                    <?php
                    $estatus = strtolower((string) ($convenio['estatus'] ?? ''));
                    $config = $estatusConfig[$estatus] ?? ['label' => ucfirst($estatus), 'class' => ''];
                    ?>
                    <span class="badge <?php echo e($config['class']); ?>">
                      <?php echo e($config['label']); ?>
                    </span>
                  </td>
                  <td data-label="Fecha de inicio"><?php echo e((string) ($convenio['fecha_inicio'] ?? '-')); ?></td>
                  <td data-label="Fecha de fin"><?php echo e((string) ($convenio['fecha_fin'] ?? '-')); ?></td>
                  <td data-label="Versión actual"><?php echo e((string) ($convenio['version_actual'] ?? '-')); ?></td>
                  <td data-label="Registrado en"><?php echo e((string) ($convenio['creado_en'] ?? '-')); ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

</body>
</html>
