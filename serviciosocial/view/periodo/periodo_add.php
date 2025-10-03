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

$formData = [
    'servicio_id' => '',
    'numero'      => '',
    'estatus'     => '',
    'abierto_en'  => '',
    'cerrado_en'  => '',
];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($formData as $key => $defaultValue) {
        $formData[$key] = trim((string)($_POST[$key] ?? ''));
    }

    try {
        $controller->create($_POST);
        header('Location: periodo_list.php?created=1');
        exit;
    } catch (\InvalidArgumentException $invalidArgumentException) {
        $errors[] = $invalidArgumentException->getMessage();
    } catch (\Throwable $throwable) {
        $errors[] = 'No fue posible registrar el periodo: ' . $throwable->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrar Nuevo Periodo · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/periodo/periodoadd.css" />
</head>
<body>

<header>
  <h1>Registrar Nuevo Periodo</h1>
  <nav class="breadcrumb">
    <a href="../../index.php">Inicio</a>
    <span>›</span>
    <a href="periodo_list.php">Gestión de Periodos</a>
    <span>›</span>
    <span>Nuevo</span>
  </nav>
</header>

<main>
  <?php if (!empty($errors)): ?>
    <div class="alert error" role="alert">
      <ul>
        <?php foreach ($errors as $error): ?>
          <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="card">
    <header>
      <h2>Información del periodo</h2>
      <p>Completa la información necesaria para crear un nuevo periodo del servicio social.</p>
    </header>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" method="post" autocomplete="off">

      <div class="grid cols-2">
        <div class="field">
          <label for="servicio_id" class="required">ID del Servicio</label>
          <input type="text" id="servicio_id" name="servicio_id" placeholder="Ej. 12345" required value="<?php echo htmlspecialchars($formData['servicio_id'], ENT_QUOTES, 'UTF-8'); ?>">
          <div class="hint">Identificador del servicio al que pertenece este periodo.</div>
        </div>

        <div class="field">
          <label for="numero" class="required">Número de periodo</label>
          <input type="number" id="numero" name="numero" placeholder="Ej. 1" min="1" required value="<?php echo htmlspecialchars($formData['numero'], ENT_QUOTES, 'UTF-8'); ?>">
          <div class="hint">Número secuencial del periodo (ej. 1, 2, 3...).</div>
        </div>

        <div class="field">
          <label for="estatus" class="required">Estatus inicial</label>
          <select id="estatus" name="estatus" required>
            <option value="">Selecciona…</option>
            <option value="abierto" <?php echo $formData['estatus'] === 'abierto' ? 'selected' : ''; ?>>Abierto</option>
            <option value="en_revision" <?php echo $formData['estatus'] === 'en_revision' ? 'selected' : ''; ?>>En revisión</option>
            <option value="completado" <?php echo $formData['estatus'] === 'completado' ? 'selected' : ''; ?>>Completado</option>
          </select>
        </div>

        <div class="field">
          <label for="abierto_en" class="required">Fecha de apertura</label>
          <input type="datetime-local" id="abierto_en" name="abierto_en" required value="<?php echo htmlspecialchars($formData['abierto_en'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>

        <div class="field">
          <label for="cerrado_en">Fecha de cierre</label>
          <input type="datetime-local" id="cerrado_en" name="cerrado_en" value="<?php echo htmlspecialchars($formData['cerrado_en'], ENT_QUOTES, 'UTF-8'); ?>">
          <div class="hint">Opcional — solo si el periodo ya está cerrado.</div>
        </div>
      </div>

      <div class="actions">
        <a href="periodo_list.php" class="btn-secondary">Cancelar</a>
        <button type="submit" class="btn-primary">Guardar Periodo</button>
      </div>

    </form>
  </div>
</main>

</body>
</html>
