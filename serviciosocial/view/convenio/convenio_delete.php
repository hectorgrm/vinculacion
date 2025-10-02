<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../controller/ConvenioController.php';

use Serviciosocial\Controller\ConvenioController;

$id = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
} else {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
}

if ($id <= 0) {
    header('Location: convenio_list.php?error=invalid');
    exit;
}

$controller = null;
$generalError = '';
$convenio = null;
$confirmation = '';

try {
    $controller = new ConvenioController();
} catch (\Throwable $exception) {
    $generalError = 'No fue posible preparar la eliminación: ' . $exception->getMessage();
}

if ($controller instanceof ConvenioController && $generalError === '') {
    try {
        $convenio = $controller->findById($id);
        if ($convenio === null) {
            header('Location: convenio_list.php?error=notfound');
            exit;
        }
    } catch (\Throwable $exception) {
        $generalError = 'No fue posible obtener la información del convenio: ' . $exception->getMessage();
    }
}

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && $controller instanceof ConvenioController
    && $generalError === ''
    && $convenio !== null
) {
    $confirmation = trim((string) ($_POST['confirmacion'] ?? ''));

    if (strcasecmp($confirmation, 'ELIMINAR') !== 0) {
        $generalError = 'Debes escribir "ELIMINAR" para confirmar la eliminación del convenio.';
    } else {
        try {
            $controller->delete($id);
            header('Location: convenio_list.php?deleted=1');
            exit;
        } catch (\Throwable $exception) {
            $generalError = 'No fue posible eliminar el convenio: ' . $exception->getMessage();
        }
    }
}

$estatusConfig = [
    'pendiente' => ['label' => 'Pendiente', 'class' => 'status pendiente'],
    'vigente' => ['label' => 'Vigente', 'class' => 'status vigente'],
    'vencido' => ['label' => 'Vencido', 'class' => 'status vencido'],
];

/**
 * @param string $value
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$formatNullable = static function (mixed $value): string {
    if ($value === null) {
        return '-';
    }

    $value = trim((string) $value);

    return $value === '' ? '-' : $value;
};

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Eliminar Convenio - Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/convenios/conveniostyles.css" />
</head>
<body>

  <header style="background: var(--danger-color);">
    <h1>Eliminar Convenio</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="convenio_list.php">Convenios</a>
      <span>›</span>
      <span>Eliminar</span>
    </nav>
  </header>

  <main>
    <section class="card danger-zone">
      <h2>⚠️ Confirmación de eliminación</h2>
      <div class="alert">
        Estás a punto de <strong>eliminar permanentemente</strong> este convenio.
        Esta acción <strong>no se puede deshacer</strong>. Asegúrate de que ya no haya estudiantes asignados a este convenio ni documentos relacionados.
      </div>

      <?php if ($generalError !== ''): ?>
        <div class="alert alert-error" role="alert" style="margin-top: 20px;">
          <?php echo e($generalError); ?>
        </div>
      <?php endif; ?>

      <?php if ($convenio !== null): ?>
        <dl class="details">
          <dt>ID del convenio:</dt>
          <dd><?php echo (int) ($convenio['id'] ?? 0); ?></dd>

          <dt>Empresa:</dt>
          <dd><?php echo e($formatNullable($convenio['empresa_nombre'] ?? '')); ?></dd>

          <dt>Estatus:</dt>
          <dd>
            <?php
            $estatus = strtolower(trim((string) ($convenio['estatus'] ?? '')));
            $estatusInfo = $estatusConfig[$estatus] ?? [
                'label' => $estatus === '' ? '-' : ucfirst($estatus),
                'class' => 'status',
            ];
            ?>
            <span class="<?php echo e($estatusInfo['class']); ?>"><?php echo e($estatusInfo['label']); ?></span>
          </dd>

          <dt>Fecha de inicio:</dt>
          <dd><?php echo e($formatNullable($convenio['fecha_inicio'] ?? null)); ?></dd>

          <dt>Fecha de fin:</dt>
          <dd><?php echo e($formatNullable($convenio['fecha_fin'] ?? null)); ?></dd>

          <dt>Versión actual:</dt>
          <dd><?php echo e($formatNullable($convenio['version_actual'] ?? null)); ?></dd>
        </dl>

        <form action="<?php echo e((string) ($_SERVER['REQUEST_URI'] ?? '')); ?>" method="post" class="form" style="margin-top: 30px;">
          <input type="hidden" name="id" value="<?php echo (int) $id; ?>" />

          <div class="field">
            <label for="confirmacion" class="required">Escribe <strong>ELIMINAR</strong> para confirmar</label>
            <input
              type="text"
              id="confirmacion"
              name="confirmacion"
              placeholder="Escribe ELIMINAR aquí..."
              value="<?php echo e($confirmation); ?>"
              required
              autocomplete="off"
            />
            <div class="hint">Esta medida es para evitar eliminaciones accidentales.</div>
          </div>

          <div class="actions">
            <a href="convenio_list.php" class="btn btn-secondary">↩️ Cancelar</a>
            <button type="submit" class="btn btn-danger">🗑️ Eliminar Convenio</button>
          </div>
        </form>
      <?php else: ?>
        <div class="alert" style="margin-top: 30px;">
          No fue posible cargar la información del convenio. Regresa a la lista e inténtalo nuevamente.
        </div>
        <div class="actions" style="margin-top: 20px;">
          <a href="convenio_list.php" class="btn btn-secondary">↩️ Volver a la lista</a>
        </div>
      <?php endif; ?>
    </section>
  </main>

</body>
</html>
