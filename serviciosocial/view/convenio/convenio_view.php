<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../controller/ConvenioController.php';

use Serviciosocial\Controller\ConvenioController;

/**
 * @param string $value
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header('Location: convenio_list.php?error=invalid');
    exit;
}

$fatalError = '';
$convenio = null;

try {
    $controller = new ConvenioController();
    $convenio = $controller->findById($id);

    if ($convenio === null) {
        header('Location: convenio_list.php?error=notfound');
        exit;
    }
} catch (\InvalidArgumentException $invalidArgumentException) {
    header('Location: convenio_list.php?error=invalid');
    exit;
} catch (\Throwable $exception) {
    $fatalError = 'No fue posible cargar la información del convenio: ' . $exception->getMessage();
}

$estatusConfig = [
    'pendiente' => ['label' => 'Pendiente', 'badge' => 'pending'],
    'vigente' => ['label' => 'Vigente', 'badge' => 'active'],
    'vencido' => ['label' => 'Vencido', 'badge' => 'expired'],
];

/**
 * @param mixed $value
 */
function formatDate(mixed $value): string
{
    if (!is_string($value) || trim($value) === '') {
        return '-';
    }

    $date = date_create($value);
    if ($date === false) {
        return '-';
    }

    return $date->format('d/m/Y');
}

$empresaNombreRaw = trim((string) ($convenio['empresa_nombre'] ?? ''));
$estatusRaw = strtolower((string) ($convenio['estatus'] ?? ''));
$estatus = $estatusConfig[$estatusRaw] ?? ['label' => ucfirst($estatusRaw), 'badge' => ''];
$fechaInicio = formatDate($convenio['fecha_inicio'] ?? null);
$fechaFin = formatDate($convenio['fecha_fin'] ?? null);
$versionActualRaw = trim((string) ($convenio['version_actual'] ?? ''));
$creadoEn = formatDate($convenio['creado_en'] ?? null);

$pageTitle = 'Detalle de Convenio';
if ($empresaNombreRaw !== '') {
    $pageTitle .= ' · ' . $empresaNombreRaw;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo e($pageTitle); ?></title>
  <link rel="stylesheet" href="../../assets/css/convenios/convenio_view.css" />
</head>

<body>
  <header>
    <h1>Servicio Social · Detalle de Convenio</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span class="sep">›</span>
      <a href="convenio_list.php">Convenios</a>
      <span class="sep">›</span>
      <span>Detalle</span>
    </nav>
  </header>

  <main>
    <?php if ($fatalError !== ''): ?>
      <div class="alert error" role="alert">
        <?php echo e($fatalError); ?>
      </div>
    <?php else: ?>
      <section class="card">
        <div class="card-header">
          <div>
            <h2>
              <?php if ($empresaNombreRaw !== ''): ?>
                <?php echo e($empresaNombreRaw); ?>
              <?php else: ?>
                Convenio #<?php echo (int) ($convenio['id'] ?? 0); ?>
              <?php endif; ?>
            </h2>
            <p class="subtitle">ID Convenio: <?php echo (int) ($convenio['id'] ?? 0); ?></p>
          </div>
          <span class="badge <?php echo e($estatus['badge']); ?>"><?php echo e((string) $estatus['label']); ?></span>
        </div>

        <div class="grid">
          <div class="field">
            <span class="label">Empresa</span>
            <p>
              <?php if ($empresaNombreRaw !== ''): ?>
                <?php echo e($empresaNombreRaw); ?>
              <?php else: ?>
                <span class="empty">Sin información</span>
              <?php endif; ?>
            </p>
          </div>
          <div class="field">
            <span class="label">Estatus</span>
            <p>
              <span class="badge inline <?php echo e($estatus['badge']); ?>"><?php echo e((string) $estatus['label']); ?></span>
            </p>
          </div>
          <div class="field">
            <span class="label">Fecha de inicio</span>
            <p><?php echo e($fechaInicio); ?></p>
          </div>
          <div class="field">
            <span class="label">Fecha de fin</span>
            <p><?php echo e($fechaFin); ?></p>
          </div>
          <div class="field">
            <span class="label">Versión</span>
            <p>
              <?php if ($versionActualRaw !== ''): ?>
                <?php echo e($versionActualRaw); ?>
              <?php else: ?>
                <span class="empty">Sin información</span>
              <?php endif; ?>
            </p>
          </div>
          <div class="field">
            <span class="label">Registrado el</span>
            <p><?php echo e($creadoEn); ?></p>
          </div>
        </div>

        <div class="actions">
          <a href="convenio_edit.php?id=<?php echo (int) ($convenio['id'] ?? 0); ?>" class="btn primary">✏️ Editar</a>
          <a href="convenio_delete.php?id=<?php echo (int) ($convenio['id'] ?? 0); ?>" class="btn danger">🗑️ Eliminar</a>
          <a href="convenio_list.php" class="btn secondary">← Volver al listado</a>
        </div>
      </section>
    <?php endif; ?>
  </main>
</body>

</html>
