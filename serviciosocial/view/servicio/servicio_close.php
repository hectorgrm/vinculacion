<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../controller/ServicioController.php';

use Serviciosocial\Controller\ServicioController;

$user = $_SESSION['user'] ?? null;
$allowedRoles = ['ss_admin', 'admin_ss'];

if (!is_array($user) || !in_array(strtolower((string) ($user['role'] ?? '')), $allowedRoles, true)) {
    header('Location: ../../../common/login.php?module=serviciosocial&error=unauthorized');
    exit;
}

$controller = new ServicioController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idParam = $_POST['id'] ?? null;
} else {
    $idParam = $_GET['id'] ?? null;
}

$servicioId = is_numeric($idParam) ? (int) $idParam : 0;

if ($servicioId <= 0) {
    header('Location: servicio_list.php?error=invalid');
    exit;
}

$fatalError = '';
$errors = [];
$servicio = null;
$formData = [
    'nuevo_estado' => '',
    'motivo'       => '',
    'fecha_cierre' => '',
];

try {
    $servicio = $controller->findServicio($servicioId);

    if ($servicio === null) {
        header('Location: servicio_list.php?error=notfound');
        exit;
    }
} catch (\Throwable $exception) {
    $fatalError = 'No fue posible obtener la información del servicio: ' . $exception->getMessage();
}

if ($fatalError === '' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($formData as $field => $default) {
        $formData[$field] = isset($_POST[$field]) ? trim((string) $_POST[$field]) : $default;
    }

    $motivo = $formData['motivo'];
    $fechaCierre = $formData['fecha_cierre'];

    if ($fechaCierre !== '') {
        $motivo = $motivo !== ''
            ? $motivo . PHP_EOL . 'Fecha de cierre: ' . $fechaCierre
            : 'Fecha de cierre: ' . $fechaCierre;
    }

    try {
        $controller->closeServicio($servicioId, $formData['nuevo_estado'], $motivo);
        header('Location: servicio_view.php?id=' . rawurlencode((string) $servicioId) . '&closed=1');
        exit;
    } catch (\InvalidArgumentException $invalidArgumentException) {
        $errors[] = $invalidArgumentException->getMessage();
    } catch (\Throwable $throwable) {
        $errors[] = 'No fue posible cerrar el servicio: ' . $throwable->getMessage();
    }
}

$formatDate = static function (?string $value): string {
    if ($value === null || $value === '') {
        return '-';
    }

    $date = date_create($value);
    if ($date === false) {
        return '-';
    }

    return $date->format('d/m/Y');
};

$statusConfig = [
    'prealta'   => ['label' => 'Pre-alta', 'class' => 'prealta'],
    'activo'    => ['label' => 'Activo', 'class' => 'activo'],
    'concluido' => ['label' => 'Concluido', 'class' => 'concluido'],
    'cancelado' => ['label' => 'Cancelado', 'class' => 'cancelado'],
];

$estudiante = null;
$plaza = null;
$horasAcumuladas = 0;
$horasRequeridas = null;
$horasLabel = '0';
$estatusKey = '';
$estatus = ['label' => '', 'class' => ''];
$isClosed = false;

if (is_array($servicio)) {
    $estudiante = $servicio['estudiante'] ?? null;
    $plaza = $servicio['plaza'] ?? null;
    $horasAcumuladas = (int) ($servicio['horas_acumuladas'] ?? 0);
    $horasRequeridas = isset($estudiante['horas_requeridas']) && $estudiante['horas_requeridas'] !== null
        ? (int) $estudiante['horas_requeridas']
        : null;

    $horasLabel = $horasRequeridas !== null
        ? sprintf('%d / %d', $horasAcumuladas, $horasRequeridas)
        : (string) $horasAcumuladas;

    $estatusKey = strtolower((string) ($servicio['estatus'] ?? ''));
    $estatus = $statusConfig[$estatusKey] ?? ['label' => ucfirst($estatusKey), 'class' => ''];

    $isClosed = in_array($estatusKey, ['concluido', 'cancelado'], true);

    if ($formData['nuevo_estado'] === '' && $isClosed) {
        $formData['nuevo_estado'] = $estatusKey;
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cerrar / Finalizar Servicio · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/servicio/globalservicio.css" />
</head>
<body>

  <header class="danger-header">
    <h1>Cerrar o Cancelar Servicio</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="servicio_list.php">Gestión de Servicios</a>
      <span>›</span>
      <span>Cerrar</span>
    </nav>
  </header>

  <main>
    <a href="servicio_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

    <?php if ($fatalError !== ''): ?>
      <div class="alert alert-error" role="alert">
        <?php echo htmlspecialchars($fatalError, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php else: ?>

      <?php if ($isClosed): ?>
        <div class="alert alert-warning" role="alert">
          El servicio ya se encuentra en estado "<?php echo htmlspecialchars($estatus['label'], ENT_QUOTES, 'UTF-8'); ?>".
        </div>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-error" role="alert">
          <ul>
            <?php foreach ($errors as $error): ?>
              <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <section class="card form-card--danger">
        <h2>Confirmar cierre del servicio</h2>
        <p>
          Estás a punto de <strong>cerrar o cancelar</strong> el siguiente servicio.
          Esta acción es <strong>irreversible</strong> y cambiará su estado permanentemente.
        </p>

        <div class="grid cols-2 resumen-servicio">
          <div class="field">
            <label>ID del Servicio</label>
            <p><?php echo (int) $servicio['id']; ?></p>
          </div>
          <div class="field">
            <label>Estudiante</label>
            <p><?php echo htmlspecialchars((string) ($estudiante['nombre'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
          <div class="field">
            <label>Matrícula</label>
            <p><?php echo htmlspecialchars((string) ($estudiante['matricula'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
          <div class="field">
            <label>Plaza asignada</label>
            <p><?php echo htmlspecialchars((string) ($plaza['nombre'] ?? 'Sin asignar'), ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
          <div class="field">
            <label>Estado actual</label>
            <p><span class="status <?php echo htmlspecialchars((string) $estatus['class'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string) $estatus['label'], ENT_QUOTES, 'UTF-8'); ?></span></p>
          </div>
          <div class="field">
            <label>Horas acumuladas</label>
            <p><?php echo htmlspecialchars($horasLabel, ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
          <div class="field">
            <label>Fecha de creación</label>
            <p><?php echo htmlspecialchars($formatDate($servicio['creado_en'] ?? null), ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
        </div>

        <form action="" method="post" class="form">
          <input type="hidden" name="id" value="<?php echo (int) $servicioId; ?>">

          <div class="field">
            <label for="nuevo_estado" class="required">Nuevo estado</label>
            <select id="nuevo_estado" name="nuevo_estado" required <?php echo $isClosed ? 'disabled' : ''; ?>>
              <option value="">-- Seleccionar --</option>
              <option value="concluido" <?php echo $formData['nuevo_estado'] === 'concluido' ? 'selected' : ''; ?>>✅ Concluido (Servicio finalizado correctamente)</option>
              <option value="cancelado" <?php echo $formData['nuevo_estado'] === 'cancelado' ? 'selected' : ''; ?>>❌ Cancelado (Servicio terminado sin concluir)</option>
            </select>
          </div>

          <div class="field">
            <label for="motivo">Motivo del cierre / comentarios</label>
            <textarea id="motivo" name="motivo" placeholder="Escribe una explicación breve del motivo..." <?php echo $isClosed ? 'disabled' : ''; ?>><?php echo htmlspecialchars($formData['motivo'], ENT_QUOTES, 'UTF-8'); ?></textarea>
          </div>

          <div class="field">
            <label for="fecha_cierre">Fecha de cierre</label>
            <input type="date" id="fecha_cierre" name="fecha_cierre" value="<?php echo htmlspecialchars($formData['fecha_cierre'], ENT_QUOTES, 'UTF-8'); ?>" <?php echo $isClosed ? 'disabled' : ''; ?> />
          </div>

          <div class="actions">
            <a href="servicio_view.php?id=<?php echo rawurlencode((string) $servicioId); ?>" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-danger" <?php echo $isClosed ? 'disabled' : ''; ?>>Cerrar Servicio</button>
          </div>
        </form>
      </section>
    <?php endif; ?>
  </main>

</body>
</html>
