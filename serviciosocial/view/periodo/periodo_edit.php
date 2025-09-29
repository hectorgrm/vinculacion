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

$controller = new PeriodoController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
} else {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
}

if ($id <= 0) {
    header('Location: periodo_list.php?error=invalid');
    exit;
}

$errors = [];
$fatalError = '';
$periodo = null;

try {
    $periodo = $controller->find($id);
    if ($periodo === null) {
        header('Location: periodo_list.php?error=notfound');
        exit;
    }
} catch (\Throwable $exception) {
    $fatalError = 'No fue posible cargar la información del periodo: ' . $exception->getMessage();
}

$statusLabels = [
    'abierto'     => 'Abierto',
    'en_revision' => 'En revisión',
    'completado'  => 'Completado',
];

$formatDateTimeForInput = static function (?string $value): string {
    if ($value === null || $value === '') {
        return '';
    }

    $date = date_create($value);
    if ($date === false) {
        return '';
    }

    return $date->format('Y-m-d\TH:i');
};

$formData = [
    'servicio_id' => (string)($periodo['servicio_id'] ?? ''),
    'numero'      => '',
    'estatus'     => '',
    'abierto_en'  => '',
    'cerrado_en'  => '',
];

if ($fatalError === '') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formData['numero'] = trim((string)($_POST['numero'] ?? ''));
        $formData['estatus'] = strtolower(trim((string)($_POST['estatus'] ?? '')));
        $formData['abierto_en'] = trim((string)($_POST['abierto_en'] ?? ''));
        $formData['cerrado_en'] = trim((string)($_POST['cerrado_en'] ?? ''));

        try {
            $controller->update($id, $_POST);
            header('Location: periodo_list.php?updated=1');
            exit;
        } catch (\InvalidArgumentException $invalidArgumentException) {
            $errors[] = $invalidArgumentException->getMessage();
        } catch (\Throwable $exception) {
            $errors[] = 'No fue posible actualizar el periodo: ' . $exception->getMessage();
        }
    } else {
        $formData['numero'] = (string)($periodo['numero'] ?? '');
        $formData['estatus'] = strtolower((string)($periodo['estatus'] ?? ''));
        $formData['abierto_en'] = $formatDateTimeForInput($periodo['abierto_en'] ?? null);
        $formData['cerrado_en'] = $formatDateTimeForInput($periodo['cerrado_en'] ?? null);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $formData['abierto_en'] === '' && isset($periodo['abierto_en'])) {
        $formData['abierto_en'] = $formatDateTimeForInput((string) $periodo['abierto_en']);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Periodo · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/periodo/periodo_edit.css" />
</head>
<body>

<header>
  <h1>Editar Periodo</h1>
  <nav class="breadcrumb">
    <a href="../../index.php">Inicio</a>
    <span>›</span>
    <a href="periodo_list.php">Gestión de Periodos</a>
    <span>›</span>
    <span>Editar</span>
  </nav>
</header>

<main>
  <?php if ($fatalError !== ''): ?>
    <div class="alert alert-error" role="alert"><?php echo htmlspecialchars($fatalError, ENT_QUOTES, 'UTF-8'); ?></div>
  <?php else: ?>
    <?php if (!empty($errors)): ?>
      <div class="alert alert-error" role="alert">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <div class="card">
      <header>
        <h2>Actualizar información del periodo</h2>
        <p>Modifica los datos necesarios y guarda los cambios.</p>
      </header>

      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" method="post" autocomplete="off">
        <input type="hidden" name="id" value="<?php echo (int) $id; ?>">

        <div class="grid cols-2">
          <div class="field">
            <label for="servicio_id" class="required">ID del Servicio</label>
            <input type="text" id="servicio_id" name="servicio_id" value="<?php echo htmlspecialchars($formData['servicio_id'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
            <div class="hint">Este campo no se puede editar.</div>
          </div>

          <div class="field">
            <label for="numero" class="required">Número de periodo</label>
            <input type="number" id="numero" name="numero" min="1" value="<?php echo htmlspecialchars($formData['numero'], ENT_QUOTES, 'UTF-8'); ?>" required>
          </div>

          <div class="field">
            <label for="estatus" class="required">Estatus actual</label>
            <select id="estatus" name="estatus" required>
              <option value="">Selecciona…</option>
              <?php foreach ($statusLabels as $value => $label): ?>
                <option value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $formData['estatus'] === $value ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="field">
            <label for="abierto_en" class="required">Fecha de apertura</label>
            <input type="datetime-local" id="abierto_en" name="abierto_en" value="<?php echo htmlspecialchars($formData['abierto_en'], ENT_QUOTES, 'UTF-8'); ?>" required>
          </div>

          <div class="field">
            <label for="cerrado_en">Fecha de cierre</label>
            <input type="datetime-local" id="cerrado_en" name="cerrado_en" value="<?php echo htmlspecialchars($formData['cerrado_en'], ENT_QUOTES, 'UTF-8'); ?>">
          </div>
        </div>

        <div class="actions">
          <a href="periodo_list.php" class="btn-secondary">Cancelar</a>
          <button type="submit" class="btn-primary">Guardar cambios</button>
        </div>

      </form>
    </div>
  <?php endif; ?>
</main>

</body>
</html>
