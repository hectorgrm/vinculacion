<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../controller/EmpresaController.php';

use Serviciosocial\Controller\EmpresaController;

$id = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
} else {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
}

if ($id <= 0) {
    header('Location: empresa_list.php?error=invalid');
    exit;
}

$controller = null;
$generalError = '';
$blockedByConvenios = false;
$empresa = null;
$confirmation = '';

try {
    $controller = new EmpresaController();
} catch (\Throwable $exception) {
    $generalError = 'No fue posible preparar la eliminación: ' . $exception->getMessage();
}

if ($controller instanceof EmpresaController && $generalError === '') {
    try {
        $empresa = $controller->findEmpresa($id);
        if ($empresa === null) {
            header('Location: empresa_list.php?error=notfound');
            exit;
        }
    } catch (\Throwable $exception) {
        $generalError = 'No fue posible obtener la información de la empresa: ' . $exception->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && $controller instanceof EmpresaController
    && $generalError === ''
    && $empresa !== null
) {
    $confirmation = trim((string) ($_POST['confirmacion'] ?? ''));

    if (strcasecmp($confirmation, 'ELIMINAR') !== 0) {
        $generalError = 'Debes escribir "ELIMINAR" para confirmar la eliminación de la empresa.';
    } else {
        try {
            $controller->deleteEmpresa($id);
            header('Location: empresa_list.php?deleted=1');
            exit;
        } catch (\RuntimeException $exception) {
            $generalError = $exception->getMessage();
            $blockedByConvenios = true;
        } catch (\Throwable $exception) {
            $generalError = 'No fue posible eliminar la empresa: ' . $exception->getMessage();
        }
    }
}

$estadoConfig = [
    'activo' => ['label' => 'Activo', 'class' => 'status activo'],
    'inactivo' => ['label' => 'Inactivo', 'class' => 'status inactivo'],
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
  <title>Eliminar Empresa · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/empresas/empresaglobalstyles.css" />
</head>
<body>

  <!-- ===== HEADER ===== -->
  <header class="danger-header">
    <h1>Eliminar Empresa</h1>
    <p>Confirma si deseas eliminar esta empresa del sistema</p>
  </header>

  <div class="container">
    <?php if ($generalError !== ''): ?>
      <div class="alert alert-error" role="alert">
        <?php echo e($generalError); ?>
        <?php if ($blockedByConvenios): ?>
          <div class="alert-actions">
            <a class="btn btn-secondary btn-sm" href="convenios_list.php?empresa_id=<?php echo (int) $id; ?>">
              Ver convenios asociados
            </a>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <?php if ($empresa !== null): ?>
      <!-- ===== ALERTA ===== -->
      <div class="form-card danger-card">
        <h2>⚠️ Acción Irreversible</h2>
        <p>
          Estás a punto de <strong>eliminar permanentemente</strong> la siguiente empresa.
          Esta acción <strong>no se puede deshacer</strong>. Todos los registros asociados podrían verse afectados.
        </p>

        <!-- ===== DATOS DE LA EMPRESA ===== -->
        <dl class="details-list">
          <dt>ID:</dt>
          <dd><?php echo (int) ($empresa['id'] ?? 0); ?></dd>

          <dt>Nombre:</dt>
          <dd><?php echo e($formatNullable($empresa['nombre'] ?? '')); ?></dd>

          <dt>Contacto:</dt>
          <dd><?php echo e($formatNullable($empresa['contacto_nombre'] ?? null)); ?></dd>

          <dt>Email:</dt>
          <dd><?php echo e($formatNullable($empresa['contacto_email'] ?? null)); ?></dd>

          <dt>Teléfono:</dt>
          <dd><?php echo e($formatNullable($empresa['telefono'] ?? null)); ?></dd>

          <dt>Estado:</dt>
          <dd>
            <?php
            $estado = strtolower(trim((string) ($empresa['estado'] ?? '')));
            $estadoInfo = $estadoConfig[$estado] ?? [
                'label' => $estado === '' ? '-' : ucfirst($estado),
                'class' => 'status',
            ];
            ?>
            <span class="<?php echo e($estadoInfo['class']); ?>"><?php echo e($estadoInfo['label']); ?></span>
          </dd>
        </dl>

        <!-- ===== FORMULARIO DE CONFIRMACIÓN ===== -->
        <form action="<?php echo e((string) ($_SERVER['REQUEST_URI'] ?? '')); ?>" method="post" class="delete-form">
          <input type="hidden" name="id" value="<?php echo (int) $id; ?>" />

          <div class="form-group">
            <label for="confirmacion" class="required">
              Escribe <strong>ELIMINAR</strong> para confirmar la eliminación:
            </label>
            <input
              type="text"
              id="confirmacion"
              name="confirmacion"
              placeholder="Escribe ELIMINAR aquí..."
              value="<?php echo e($confirmation); ?>"
              required
              autocomplete="off"
            />
            <p class="hint">Esta medida evita eliminaciones accidentales.</p>
          </div>

          <div class="form-actions">
            <a href="empresa_list.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-danger">🗑️ Eliminar Empresa</button>
          </div>
        </form>
      </div>
    <?php else: ?>
      <div class="form-card danger-card">
        <p>No fue posible cargar la información de la empresa. Regresa a la lista e inténtalo nuevamente.</p>
        <a href="empresa_list.php" class="btn btn-secondary">Volver a la lista</a>
      </div>
    <?php endif; ?>
  </div>

</body>
</html>
