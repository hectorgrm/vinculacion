<?php
declare(strict_types=1);

if (!isset($activos) || !isset($historico)) {
    require __DIR__ . '/../handler/estudiante_list_handler.php';
    return;
}

if (!function_exists('estudianteNombreCompleto')) {
    require_once __DIR__ . '/../helpers/estudiante_helper.php';
}

$empresaNombre = isset($empresaNombre) && $empresaNombre !== ''
    ? (string) $empresaNombre
    : 'Empresa';
$listErrorMessage = isset($listErrorMessage) && $listErrorMessage !== ''
    ? (string) $listErrorMessage
    : null;
$activos = is_array($activos) ? $activos : [];
$historico = is_array($historico) ? $historico : [];
$kpiActivos = isset($kpiActivos) ? (int) $kpiActivos : count($activos);
$kpiFinalizados = isset($kpiFinalizados) ? (int) $kpiFinalizados : 0;
$kpiTotal = isset($kpiTotal) ? (int) $kpiTotal : ($kpiActivos + count($historico));
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal Empresa · Estudiantes</title>

<link rel="stylesheet" href="../assets/css/estudiante/estudiante.css">

</head>
<body>

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
      <?php if ($listErrorMessage !== null): ?>
        <div class="alert error"><?= htmlspecialchars($listErrorMessage) ?></div>
      <?php endif; ?>
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
                  <?= htmlspecialchars(estudianteNombreCompleto($e)) ?>
                </td>
                <td><?= htmlspecialchars($e['carrera'] ?? '') ?></td>
                <td><?= htmlspecialchars($e['matricula'] ?? '') ?></td>
                <td><span class="badge <?= htmlspecialchars(estudianteBadgeClass($e['estatus'] ?? null)) ?>"><?= htmlspecialchars(estudianteBadgeLabel($e['estatus'] ?? null)) ?></span></td>
                <td class="actions">
                  <a class="btn small" href="estudiante_view.php?id=<?= (int) ($e['id'] ?? 0) ?>">👁️ Ver detalle</a>
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
                  <?= htmlspecialchars(estudianteNombreCompleto($e)) ?>
                </td>
                <td><?= htmlspecialchars($e['carrera'] ?? '') ?></td>
                <td><?= htmlspecialchars($e['matricula'] ?? '') ?></td>
                <td><span class="badge <?= htmlspecialchars(estudianteBadgeClass($e['estatus'] ?? null)) ?>"><?= htmlspecialchars(estudianteBadgeLabel($e['estatus'] ?? null)) ?></span></td>
                <td class="actions">
                  <a class="btn small" href="estudiante_view.php?id=<?= (int) ($e['id'] ?? 0) ?>">👁️ Ver detalle</a>
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
