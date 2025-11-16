<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal Empresa · Estudiantes</title>

<link rel="stylesheet" href="../assets/css/estudiante/estudiante.css">

</head>
<body>

<?php
// Datos de ejemplo SOLO para ver el frontend.
// Más adelante los reemplazas con la consulta a rp_estudiante.
$empresaNombre = 'Casa del Barrio';

$activos = [
  [
    'nombre'           => 'Ana',
    'apellido_paterno' => 'Rodríguez',
    'apellido_materno' => 'López',
    'carrera'          => 'Ingeniería Informática',
    'matricula'        => '20204567',
    'estado'           => 'Activo',
    'detalle'          => 'estudiante_view.php?id=101',
  ],
  [
    'nombre'           => 'Mario',
    'apellido_paterno' => 'Díaz',
    'apellido_materno' => 'Hernández',
    'carrera'          => 'Ingeniería Industrial',
    'matricula'        => '20197890',
    'estado'           => 'Activo',
    'detalle'          => 'estudiante_view.php?id=102',
  ],
];

$historico = [
  [
    'nombre'           => 'Juan',
    'apellido_paterno' => 'Pérez',
    'apellido_materno' => 'García',
    'carrera'          => 'Ingeniería Industrial',
    'matricula'        => '20181234',
    'estado'           => 'Finalizado',
    'detalle'          => 'estudiante_view.php?id=77',
  ],
  [
    'nombre'           => 'Laura',
    'apellido_paterno' => 'Méndez',
    'apellido_materno' => 'Ruiz',
    'carrera'          => 'Ingeniería Informática',
    'matricula'        => '20175678',
    'estado'           => 'Finalizado',
    'detalle'          => 'estudiante_view.php?id=66',
  ],
];

$kpiActivos     = count($activos);
$kpiFinalizados = count($historico);
$kpiTotal       = $kpiActivos + $kpiFinalizados;
?>

<header class="portal-header">
  <div class="brand">
    <div class="logo"></div>
    <div>
      <strong>Portal de Empresa</strong><br>
      <small>Residencias Profesionales</small>
    </div>
  </div>
  <div class="userbox">
    <span class="company"><?= htmlspecialchars($empresaNombre) ?></span>
    <a href="portal_list.php" class="btn">Inicio</a>
    <a href="../../common/logout.php" class="btn">Salir</a>
  </div>
</header>

<main class="container">

  <section class="titlebar">
    <div>
      <h1>Estudiantes vinculados</h1>
      <p>Consulta tus residentes activos e histórico.</p>
    </div>
    <div class="actions">
      <a href="convenio_view.php" class="btn primary">📑 Ver convenio</a>
    </div>
  </section>

  <!-- KPIs -->
  <section class="card">
    <header>Resumen</header>
    <div class="content">
      <div class="kpis">
        <div class="kpi">
          <div class="num"><?= $kpiActivos ?></div>
          <div class="lbl">Activos</div>
        </div>
        <div class="kpi">
          <div class="num"><?= $kpiFinalizados ?></div>
          <div class="lbl">Finalizados</div>
        </div>
        <div class="kpi">
          <div class="num"><?= $kpiTotal ?></div>
          <div class="lbl">Total registrados</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Listados -->
  <section class="card">
    <header>Listado</header>
    <div class="content">

      <!-- Tabs -->
      <div class="tabs">
        <button type="button" class="tab active" onclick="showTab('activos')">Activos</button>
        <button type="button" class="tab" onclick="showTab('historico')">Histórico</button>
      </div>

      <!-- Tabla Activos -->
      <div id="tab-activos" class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Carrera</th>
              <th>Matrícula</th>
              <th>Estatus</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$activos): ?>
              <tr>
                <td colspan="5">
                  <span class="hint">Sin residentes activos por ahora.</span>
                </td>
              </tr>
            <?php endif; ?>

            <?php foreach ($activos as $e): ?>
              <tr>
                <td>
                  <?= htmlspecialchars(trim($e['nombre'].' '.$e['apellido_paterno'].' '.$e['apellido_materno'])) ?>
                </td>
                <td><?= htmlspecialchars($e['carrera']) ?></td>
                <td><?= htmlspecialchars($e['matricula']) ?></td>
                <td><span class="badge info"><?= htmlspecialchars($e['estado']) ?></span></td>
                <td class="actions">
                  <a class="btn small" href="<?= htmlspecialchars($e['detalle']) ?>">👁️ Ver detalle</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Tabla Histórico -->
      <div id="tab-historico" class="table-wrap" style="display:none;">
        <table>
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Carrera</th>
              <th>Matrícula</th>
              <th>Resultado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$historico): ?>
              <tr>
                <td colspan="5">
                  <span class="hint">No hay histórico disponible.</span>
                </td>
              </tr>
            <?php endif; ?>

            <?php foreach ($historico as $e): ?>
              <tr>
                <td>
                  <?= htmlspecialchars(trim($e['nombre'].' '.$e['apellido_paterno'].' '.$e['apellido_materno'])) ?>
                </td>
                <td><?= htmlspecialchars($e['carrera']) ?></td>
                <td><?= htmlspecialchars($e['matricula']) ?></td>
                <td><span class="badge ok"><?= htmlspecialchars($e['estado']) ?></span></td>
                <td class="actions">
                  <a class="btn small" href="<?= htmlspecialchars($e['detalle']) ?>">👁️ Ver detalle</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

    </div>
  </section>

</main>

<script>
  function showTab(which) {
    const activos = document.getElementById('tab-activos');
    const historico = document.getElementById('tab-historico');
    activos.style.display = (which === 'activos') ? 'block' : 'none';
    historico.style.display = (which === 'historico') ? 'block' : 'none';

    const buttons = document.querySelectorAll('.tab');
    buttons.forEach(b => b.classList.remove('active'));
    buttons[(which === 'activos') ? 0 : 1].classList.add('active');
  }
</script>

</body>
</html>
