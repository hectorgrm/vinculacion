<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../controller/PlazaController.php';

use Serviciosocial\Controller\PlazaController;

$user = $_SESSION['user'] ?? null;
$allowedRoles = ['ss_admin'];

if (!is_array($user) || !in_array(strtolower((string)($user['role'] ?? '')), $allowedRoles, true)) {
    header('Location: ../../../common/login.php?module=serviciosocial&error=unauthorized');
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header('Location: plaza_list.php?error=invalid');
    exit;
}

$fatalError = '';
$plaza = null;

try {
    $controller = new PlazaController();
    $plaza = $controller->findById($id);

    if ($plaza === null) {
        header('Location: plaza_list.php?error=notfound');
        exit;
    }
} catch (\InvalidArgumentException $invalidArgumentException) {
    header('Location: plaza_list.php?error=invalid');
    exit;
} catch (\Throwable $throwable) {
    $fatalError = 'No fue posible cargar la información de la plaza: ' . $throwable->getMessage();
}

$modalidadLabels = [
    'presencial' => 'Presencial',
    'hibrida'    => 'Híbrida',
    'remota'     => 'Remota',
];

$estadoLabels = [
    'activa'   => 'Activa',
    'inactiva' => 'Inactiva',
];

$nombrePlaza = (string)($plaza['nombre'] ?? '');
$empresaNombre = (string)($plaza['empresa_nombre'] ?? '');
$modalidad = strtolower((string)($plaza['modalidad'] ?? ''));
$modalidadTexto = $modalidadLabels[$modalidad] ?? '-';

$cupoValor = $plaza['cupo'] ?? null;
$cupoTexto = '-';
if ($cupoValor !== null && $cupoValor !== '') {
    $cupoInt = (int) $cupoValor;
    $cupoTexto = $cupoInt . ' estudiante' . ($cupoInt === 1 ? '' : 's');
}

$periodoInicioTexto = '-';
$periodoFinTexto = '-';
$periodoTexto = '-';
$periodoInicioRaw = $plaza['periodo_inicio'] ?? null;
$periodoFinRaw = $plaza['periodo_fin'] ?? null;

if (is_string($periodoInicioRaw) && trim($periodoInicioRaw) !== '') {
    $fechaInicio = date_create($periodoInicioRaw);
    if ($fechaInicio !== false) {
        $periodoInicioTexto = $fechaInicio->format('d/m/Y');
    }
}

if (is_string($periodoFinRaw) && trim($periodoFinRaw) !== '') {
    $fechaFin = date_create($periodoFinRaw);
    if ($fechaFin !== false) {
        $periodoFinTexto = $fechaFin->format('d/m/Y');
    }
}

if ($periodoInicioTexto !== '-' && $periodoFinTexto !== '-') {
    $periodoTexto = $periodoInicioTexto . ' – ' . $periodoFinTexto;
} elseif ($periodoInicioTexto !== '-') {
    $periodoTexto = 'Desde ' . $periodoInicioTexto;
} elseif ($periodoFinTexto !== '-') {
    $periodoTexto = 'Hasta ' . $periodoFinTexto;
}

$actividades = trim((string)($plaza['actividades'] ?? ''));
$requisitos = trim((string)($plaza['requisitos'] ?? ''));
$responsableNombre = trim((string)($plaza['responsable_nombre'] ?? ''));
$responsablePuesto = trim((string)($plaza['responsable_puesto'] ?? ''));
$responsableEmail = trim((string)($plaza['responsable_email'] ?? ''));
$responsableTel = trim((string)($plaza['responsable_tel'] ?? ''));
$direccion = trim((string)($plaza['direccion'] ?? ''));
$ubicacion = trim((string)($plaza['ubicacion'] ?? ''));
$estado = strtolower((string)($plaza['estado'] ?? ''));
$estadoTexto = $estadoLabels[$estado] ?? '-';

$observacionesOriginal = trim((string)($plaza['observaciones'] ?? ''));
$horarioTexto = '';
$observacionesTexto = $observacionesOriginal;
$horarioLabel = 'Horario:';

if ($observacionesOriginal !== '') {
    $lineas = preg_split("/\r\n|\n|\r/", $observacionesOriginal) ?: [];
    if (!empty($lineas)) {
        $primeraLinea = trim((string) $lineas[0]);
        if (stripos($primeraLinea, $horarioLabel) === 0) {
            $horarioTexto = trim((string) substr($primeraLinea, strlen($horarioLabel)));
            array_shift($lineas);
            $observacionesTexto = trim(implode("\n", $lineas));
        }
    }
}

$paginaTitulo = $nombrePlaza !== '' ? $nombrePlaza : 'Detalle de plaza';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicio Social - <?php echo htmlspecialchars($paginaTitulo, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="../../assets/css/plaza/plazaviewstyles.css">
</head>
<body>
  <header>
    <h1>Servicio Social · Detalle de Plaza</h1>
    <nav class="breadcrumb">
      <a href="../../dashboard.php">Inicio</a>
      <span class="sep">›</span>
      <a href="plaza_list.php">Gestión de Plazas</a>
      <span class="sep">›</span>
      <span><?php echo htmlspecialchars($paginaTitulo, ENT_QUOTES, 'UTF-8'); ?></span>
    </nav>
  </header>

  <main>
    <?php if ($fatalError !== ''): ?>
      <div class="alert error" role="alert">
        <?php echo htmlspecialchars($fatalError, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php else: ?>
      <div class="section">
        <h2>Información general</h2>
        <div class="grid">
          <div class="field">
            <label>Nombre de la plaza</label>
            <p>
              <?php if ($nombrePlaza !== ''): ?>
                <?php echo htmlspecialchars($nombrePlaza, ENT_QUOTES, 'UTF-8'); ?>
              <?php else: ?>
                <span class="empty">Sin información</span>
              <?php endif; ?>
            </p>
          </div>
          <div class="field">
            <label>Dependencia / Empresa</label>
            <p>
              <?php if ($empresaNombre !== ''): ?>
                <?php echo htmlspecialchars($empresaNombre, ENT_QUOTES, 'UTF-8'); ?>
              <?php else: ?>
                <span class="empty">Sin información</span>
              <?php endif; ?>
            </p>
          </div>
          <div class="field">
            <label>Modalidad</label>
            <p>
              <?php if ($modalidadTexto !== '-'): ?>
                <?php echo htmlspecialchars($modalidadTexto, ENT_QUOTES, 'UTF-8'); ?>
              <?php else: ?>
                <span class="empty">Sin información</span>
              <?php endif; ?>
            </p>
          </div>
          <div class="field">
            <label>Cupo</label>
            <p>
              <?php if ($cupoTexto !== '-'): ?>
                <?php echo htmlspecialchars($cupoTexto, ENT_QUOTES, 'UTF-8'); ?>
              <?php else: ?>
                <span class="empty">Sin información</span>
              <?php endif; ?>
            </p>
          </div>
          <div class="field">
            <label>Periodo</label>
            <p>
              <?php if ($periodoTexto !== '-'): ?>
                <?php echo htmlspecialchars($periodoTexto, ENT_QUOTES, 'UTF-8'); ?>
              <?php else: ?>
                <span class="empty">Sin información</span>
              <?php endif; ?>
            </p>
          </div>
          <div class="field">
            <label>Horario</label>
            <p>
              <?php if ($horarioTexto !== ''): ?>
                <?php echo htmlspecialchars($horarioTexto, ENT_QUOTES, 'UTF-8'); ?>
              <?php else: ?>
                <span class="empty">Sin información</span>
              <?php endif; ?>
            </p>
          </div>
        </div>
      </div>

      <div class="section">
        <h2>Descripción y requisitos</h2>
        <div class="field">
          <label>Actividades a realizar</label>
          <p>
            <?php if ($actividades !== ''): ?>
              <?php echo nl2br(htmlspecialchars($actividades, ENT_QUOTES, 'UTF-8')); ?>
            <?php else: ?>
              <span class="empty">Sin información</span>
            <?php endif; ?>
          </p>
        </div>
        <div class="field">
          <label>Requisitos</label>
          <p>
            <?php if ($requisitos !== ''): ?>
              <?php echo nl2br(htmlspecialchars($requisitos, ENT_QUOTES, 'UTF-8')); ?>
            <?php else: ?>
              <span class="empty">Sin información</span>
            <?php endif; ?>
          </p>
        </div>
      </div>

      <div class="section">
        <h2>Contacto</h2>
        <div class="grid">
          <div class="field">
            <label>Responsable</label>
            <p>
              <?php if ($responsableNombre !== ''): ?>
                <?php echo htmlspecialchars($responsableNombre, ENT_QUOTES, 'UTF-8'); ?>
              <?php else: ?>
                <span class="empty">Sin información</span>
              <?php endif; ?>
            </p>
          </div>
          <div class="field">
            <label>Puesto</label>
            <p>
              <?php if ($responsablePuesto !== ''): ?>
                <?php echo htmlspecialchars($responsablePuesto, ENT_QUOTES, 'UTF-8'); ?>
              <?php else: ?>
                <span class="empty">Sin información</span>
              <?php endif; ?>
            </p>
          </div>
          <div class="field">
            <label>Correo</label>
            <p>
              <?php if ($responsableEmail !== ''): ?>
                <a href="mailto:<?php echo htmlspecialchars($responsableEmail, ENT_QUOTES, 'UTF-8'); ?>">
                  <?php echo htmlspecialchars($responsableEmail, ENT_QUOTES, 'UTF-8'); ?>
                </a>
              <?php else: ?>
                <span class="empty">Sin información</span>
              <?php endif; ?>
            </p>
          </div>
          <div class="field">
            <label>Teléfono</label>
            <p>
              <?php if ($responsableTel !== ''): ?>
                <a href="tel:<?php echo htmlspecialchars($responsableTel, ENT_QUOTES, 'UTF-8'); ?>">
                  <?php echo htmlspecialchars($responsableTel, ENT_QUOTES, 'UTF-8'); ?>
                </a>
              <?php else: ?>
                <span class="empty">Sin información</span>
              <?php endif; ?>
            </p>
          </div>
        </div>
      </div>

      <div class="section">
        <h2>Ubicación y estado</h2>
        <div class="grid">
          <div class="field">
            <label>Dirección</label>
            <p>
              <?php if ($direccion !== ''): ?>
                <?php echo nl2br(htmlspecialchars($direccion, ENT_QUOTES, 'UTF-8')); ?>
              <?php else: ?>
                <span class="empty">Sin información</span>
              <?php endif; ?>
            </p>
          </div>
          <div class="field">
            <label>Ubicación</label>
            <p>
              <?php if ($ubicacion !== ''): ?>
                <?php echo htmlspecialchars($ubicacion, ENT_QUOTES, 'UTF-8'); ?>
              <?php else: ?>
                <span class="empty">Sin información</span>
              <?php endif; ?>
            </p>
          </div>
          <div class="field">
            <label>Estado</label>
            <p>
              <?php if ($estadoTexto !== '-'): ?>
                <?php echo htmlspecialchars($estadoTexto, ENT_QUOTES, 'UTF-8'); ?>
              <?php else: ?>
                <span class="empty">Sin información</span>
              <?php endif; ?>
            </p>
          </div>
          <div class="field">
            <label>Observaciones</label>
            <p>
              <?php if ($observacionesTexto !== ''): ?>
                <?php echo nl2br(htmlspecialchars($observacionesTexto, ENT_QUOTES, 'UTF-8')); ?>
              <?php else: ?>
                <span class="empty">Sin información</span>
              <?php endif; ?>
            </p>
          </div>
        </div>
      </div>

      <div class="actions">
        <a class="btn ghost" href="plaza_list.php">Volver</a>
        <a class="btn primary" href="plaza_edit.php?id=<?php echo (int) $id; ?>">Editar</a>
      </div>
    <?php endif; ?>
  </main>
</body>
</html>
