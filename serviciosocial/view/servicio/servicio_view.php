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

$idParam = $_GET['id'] ?? null;
$servicioId = is_numeric($idParam) ? (int) $idParam : 0;

$error = '';
$servicio = null;

try {
    if ($servicioId <= 0) {
        throw new \RuntimeException('Identificador de servicio inválido.');
    }

    $controller = new ServicioController();
    $servicio = $controller->findServicio($servicioId);

    if ($servicio === null) {
        throw new \RuntimeException('El servicio solicitado no existe.');
    }
} catch (\Throwable $exception) {
    $error = $exception->getMessage();
}

$formatDate = static function (?string $value, string $format = 'd/m/Y'): string {
    if ($value === null || $value === '') {
        return '-';
    }

    $date = date_create($value);
    if ($date === false) {
        return '-';
    }

    return $date->format($format);
};

$formatDateTime = static function (?string $value) use ($formatDate): string {
    return $formatDate($value, 'd/m/Y H:i');
};

$statusConfig = [
    'prealta'   => ['label' => 'Pre-alta', 'class' => 'prealta'],
    'activo'    => ['label' => 'Activo', 'class' => 'activo'],
    'concluido' => ['label' => 'Concluido', 'class' => 'concluido'],
    'cancelado' => ['label' => 'Cancelado', 'class' => 'cancelado'],
];

$horasLabel = '-';
$estatus = '';
$estatusClass = '';
$estatusLabel = '';
$estudiante = null;
$plaza = null;
$periodos = [];
$observaciones = '';

if ($error === '' && $servicio !== null) {
    $estudiante = $servicio['estudiante'] ?? null;
    $plaza = $servicio['plaza'] ?? null;
    $periodos = $servicio['periodos'] ?? [];

    $horasAcumuladas = $servicio['horas_acumuladas'] ?? 0;
    $horasRequeridas = $estudiante['horas_requeridas'] ?? null;
    if ($horasRequeridas !== null) {
        $horasLabel = sprintf('%d / %d horas', (int) $horasAcumuladas, (int) $horasRequeridas);
    } else {
        $horasLabel = sprintf('%d horas registradas', (int) $horasAcumuladas);
    }

    $estatus = strtolower((string) ($servicio['estatus'] ?? ''));
    $estatusData = $statusConfig[$estatus] ?? ['label' => ucfirst($estatus), 'class' => ''];
    $estatusLabel = $estatusData['label'];
    $estatusClass = $estatusData['class'];

    $observaciones = trim((string) ($servicio['observaciones'] ?? ''));
}

function escapeWithBreaks(string $value): string
{
    $safe = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    return nl2br($safe, false);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detalle del Servicio · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/servicio/globalservicio.css" />
</head>
<body>

  <header>
    <h1>Detalle del Servicio</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="servicio_list.php">Gestión de Servicios</a>
      <span>›</span>
      <span>Detalle</span>
    </nav>
  </header>

  <main>
    <a href="servicio_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

    <?php if ($error !== ''): ?>
      <div class="alert alert-error" role="alert">
        <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php else: ?>

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
          <div class="field">
            <label>Correo electrónico</label>
            <p><?php echo htmlspecialchars((string) ($estudiante['email'] ?? 'No registrado'), ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
          <div class="field">
            <label>Teléfono</label>
            <p><?php echo htmlspecialchars((string) ($estudiante['telefono'] ?? 'No registrado'), ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
        </div>
      </section>

      <section class="card">
        <h2>Plaza Asignada</h2>
        <div class="grid cols-2">
          <div class="field">
            <label>Nombre de la plaza</label>
            <p><?php echo htmlspecialchars((string) ($plaza['nombre'] ?? 'Sin asignar'), ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
          <div class="field">
            <label>Dependencia / Empresa</label>
            <p><?php echo htmlspecialchars((string) ($plaza['empresa'] ?? 'No registrada'), ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
          <div class="field">
            <label>Modalidad</label>
            <p><?php echo htmlspecialchars((string) ($plaza['modalidad'] ?? 'No registrada'), ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
          <div class="field">
            <label>Cupo</label>
            <p><?php echo htmlspecialchars($plaza['cupo'] !== null ? (string) $plaza['cupo'] : 'No registrado', ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
          <div class="field">
            <label>Periodo de plaza</label>
            <p>
              <?php
              $inicio = $formatDate($plaza['periodo_inicio'] ?? null);
              $fin = $formatDate($plaza['periodo_fin'] ?? null);
              echo htmlspecialchars($inicio . ' – ' . $fin, ENT_QUOTES, 'UTF-8');
              ?>
            </p>
          </div>
        </div>
      </section>

      <section class="card">
        <h2>Detalles del Servicio</h2>
        <div class="grid cols-2">
          <div class="field">
            <label>ID del Servicio</label>
            <p><?php echo (int) ($servicio['id'] ?? 0); ?></p>
          </div>
          <div class="field">
            <label>Estado actual</label>
            <p><span class="status <?php echo htmlspecialchars($estatusClass, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($estatusLabel, ENT_QUOTES, 'UTF-8'); ?></span></p>
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
      </section>

      <section class="card">
        <h2>Periodos del Servicio</h2>
        <table class="styled-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Estatus</th>
              <th>Abierto en</th>
              <th>Cerrado en</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($periodos)): ?>
              <tr>
                <td colspan="4">Este servicio no cuenta con periodos registrados.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($periodos as $periodo): ?>
                <?php
                $periodoStatusKey = strtolower((string) ($periodo['estatus'] ?? ''));
                $periodoStatus = [
                    'abierto'     => ['label' => 'Abierto', 'class' => 'activo'],
                    'en_revision' => ['label' => 'En revisión', 'class' => 'en_revision'],
                    'completado'  => ['label' => 'Completado', 'class' => 'completado'],
                ][$periodoStatusKey] ?? ['label' => ucfirst($periodoStatusKey), 'class' => ''];
                ?>
                <tr>
                  <td><?php echo htmlspecialchars((string) ($periodo['numero'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><span class="status <?php echo htmlspecialchars($periodoStatus['class'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($periodoStatus['label'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                  <td><?php echo htmlspecialchars($formatDateTime($periodo['abierto_en'] ?? null), ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($formatDateTime($periodo['cerrado_en'] ?? null), ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </section>

      <section class="card">
        <h2>Observaciones</h2>
        <p>
          <?php echo $observaciones !== '' ? escapeWithBreaks($observaciones) : 'Sin observaciones registradas.'; ?>
        </p>
      </section>

      <div class="actions">
        <a href="servicio_edit.php?id=<?php echo rawurlencode((string) $servicioId); ?>" class="btn btn-warning">Editar</a>
        <a href="servicio_close.php?id=<?php echo rawurlencode((string) $servicioId); ?>" class="btn btn-danger">Finalizar Servicio</a>
      </div>

    <?php endif; ?>
  </main>

</body>
</html>
