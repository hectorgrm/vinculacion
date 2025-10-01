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
$estudiante = null;
$formData = [
    'plaza'         => '',
    'estatus'       => 'prealta',
    'horas'         => '',
    'observaciones' => '',
];

try {
    $servicio = $controller->findServicio($servicioId);
    if ($servicio === null) {
        header('Location: servicio_list.php?error=notfound');
        exit;
    }

    $estudiante = $servicio['estudiante'] ?? null;
} catch (\Throwable $exception) {
    $fatalError = 'No fue posible obtener la información del servicio: ' . $exception->getMessage();
}

$plazas = [];

if ($fatalError === '') {
    try {
        $plazas = $controller->getPlazasCatalog();
    } catch (\Throwable $catalogException) {
        $fatalError = 'No fue posible cargar el catálogo de plazas: ' . $catalogException->getMessage();
    }
}

if ($fatalError === '') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        foreach ($formData as $key => $default) {
            $formData[$key] = isset($_POST[$key]) ? trim((string) $_POST[$key]) : $default;
        }

        try {
            $controller->updateServicio($servicioId, $_POST);
            header('Location: servicio_view.php?id=' . rawurlencode((string) $servicioId) . '&updated=1');
            exit;
        } catch (\InvalidArgumentException $invalidArgumentException) {
            $errors[] = $invalidArgumentException->getMessage();
        } catch (\Throwable $throwable) {
            $errors[] = 'No fue posible actualizar el servicio: ' . $throwable->getMessage();
        }
    } else {
        $formData['plaza'] = isset($servicio['plaza']['id']) ? (string) $servicio['plaza']['id'] : '';
        $formData['estatus'] = strtolower((string) ($servicio['estatus'] ?? 'prealta'));
        $formData['horas'] = $servicio['horas_acumuladas'] !== null ? (string) $servicio['horas_acumuladas'] : '0';
        $formData['observaciones'] = (string) ($servicio['observaciones'] ?? '');
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

$statusOptions = [
    'prealta'   => 'Pre-alta',
    'activo'    => 'Activo',
    'concluido' => 'Concluido',
    'cancelado' => 'Cancelado',
];

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Servicio · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/servicio/globalservicio.css" />
</head>
<body>

  <header>
    <h1>Editar Servicio</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="servicio_list.php">Gestión de Servicios</a>
      <span>›</span>
      <span>Editar</span>
    </nav>
  </header>

  <main>
    <a href="servicio_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

    <?php if ($fatalError !== ''): ?>
      <div class="alert alert-error" role="alert">
        <?php echo htmlspecialchars($fatalError, ENT_QUOTES, 'UTF-8'); ?>
      </div>
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

      <section class="card">
        <h2>Datos del Estudiante</h2>
        <div class="grid cols-2">
          <div class="field">
            <label>Nombre completo</label>
            <p><?php echo htmlspecialchars((string) ($estudiante['nombre'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
          <div class="field">
            <label>Matrícula</label>
            <p><?php echo htmlspecialchars((string) ($estudiante['matricula'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
          <div class="field">
            <label>Carrera</label>
            <p><?php echo htmlspecialchars((string) ($estudiante['carrera'] ?? 'No registrada'), ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
        </div>
      </section>

      <section class="card">
        <h2>Editar Detalles del Servicio</h2>

        <form action="" method="post" class="form">
          <input type="hidden" name="id" value="<?php echo (int) $servicioId; ?>">

          <div class="grid cols-2">

            <div class="field">
              <label for="plaza">Plaza asignada</label>
              <select id="plaza" name="plaza">
                <option value="">-- Seleccionar plaza --</option>
                <?php foreach ($plazas as $plaza): ?>
                  <?php
                    $value = (string) $plaza['id'];
                    $selected = $value === $formData['plaza'] ? 'selected' : '';
                    $empresa = trim((string) ($plaza['empresa'] ?? ''));
                    $label = trim((string) $plaza['nombre']);
                    if ($empresa !== '') {
                        $label .= ' · ' . $empresa;
                    }
                  ?>
                  <option value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $selected; ?>>
                    <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="field">
              <label for="estatus" class="required">Estado del servicio</label>
              <select id="estatus" name="estatus" required>
                <?php foreach ($statusOptions as $value => $label): ?>
                  <option value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $formData['estatus'] === $value ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="field">
              <label for="horas" class="required">Horas acumuladas</label>
              <input type="number" id="horas" name="horas" placeholder="Ej. 320" value="<?php echo htmlspecialchars($formData['horas'], ENT_QUOTES, 'UTF-8'); ?>" min="0" />
            </div>

            <div class="field">
              <label>Fecha de creación</label>
              <p><?php echo htmlspecialchars($formatDate($servicio['creado_en'] ?? null), ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
          </div>

          <div class="field" style="grid-column: 1 / -1;">
            <label for="observaciones">Observaciones</label>
            <textarea id="observaciones" name="observaciones" placeholder="Notas adicionales sobre el servicio..." rows="4"><?php echo htmlspecialchars($formData['observaciones'], ENT_QUOTES, 'UTF-8'); ?></textarea>
            <?php if ($servicio['observaciones'] === null): ?>
              <div class="hint">(Este campo se guardará sólo si la columna existe en la base de datos).</div>
            <?php endif; ?>
          </div>

          <div class="actions">
            <a href="servicio_list.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
          </div>
        </form>
      </section>
    <?php endif; ?>
  </main>

</body>
</html>
