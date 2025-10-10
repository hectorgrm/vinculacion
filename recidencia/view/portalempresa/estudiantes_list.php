<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal Empresa · Estudiantes</title>

  <!-- HTML + CSS JUNTOS -->
  <style>
    :root{
      --primary:#1f6feb; --primary-600:#1656b8;
      --ink:#0f172a; --muted:#64748b;
      --panel:#ffffff; --bg:#f6f8fb; --border:#e5e7eb;
      --ok:#16a34a; --warn:#f59e0b; --danger:#e11d48;
      --radius:16px; --shadow:0 6px 20px rgba(2,6,23,.06);
    }
    *{box-sizing:border-box}
    body{margin:0;font-family:Inter,system-ui,Segoe UI,Roboto,Arial,sans-serif;background:var(--bg);color:var(--ink)}

    /* Header */
    .portal-header{background:#fff;border-bottom:1px solid var(--border);padding:14px 18px;display:flex;justify-content:space-between;align-items:center}
    .brand{display:flex;gap:12px;align-items:center}
    .logo{width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,var(--primary),#6ea8ff);box-shadow:0 2px 10px rgba(31,111,235,.25)}
    .userbox{display:flex;gap:8px;align-items:center}
    .userbox .company{font-weight:700;color:#334155}

    /* Utils */
    .container{max-width:1200px;margin:20px auto;padding:0 14px}
    .btn{display:inline-block;border:1px solid var(--border);background:#fff;padding:10px 14px;border-radius:12px;text-decoration:none;color:var(--ink);font-weight:600;cursor:pointer}
    .btn:hover{background:#f8fafc}.btn.primary{background:var(--primary);border-color:var(--primary);color:#fff}
    .btn.primary:hover{background:var(--primary-600)}
    .btn.small{padding:8px 10px;font-size:13px}
    .badge{display:inline-block;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:700}
    .badge.ok{background:#dcfce7;color:#166534}
    .badge.warn{background:#fff7ed;color:#9a3412;border:1px solid #fed7aa}
    .badge.info{background:#e0f2fe;color:#075985;border:1px solid #bae6fd}
    .hint{color:var(--muted);font-size:12px}

    .titlebar{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:14px}
    .titlebar h1{margin:0 0 4px 0;font-size:22px}
    .titlebar p{margin:0;color:var(--muted)}

    .card{background:var(--panel);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow)}
    .card header{padding:12px 14px;border-bottom:1px solid var(--border);font-weight:700}
    .card .content{padding:14px}

    /* KPIs */
    .kpis{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
    .kpi{background:#f8fafc;border:1px solid var(--border);border-radius:12px;padding:12px;text-align:center}
    .kpi .num{font-size:22px;font-weight:800}
    .kpi .lbl{font-size:12px;color:var(--muted)}

    /* Filters + Tabs */
    .filters{display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;margin-bottom:10px}
    .filters .field{display:flex;flex-direction:column;gap:6px}
    .filters input, .filters select{height:38px;padding:8px 10px;border:1px solid var(--border);border-radius:10px}
    .filters label{font-size:13px;color:#475569}

    .tabs{display:flex;gap:8px;margin:6px 0 12px}
    .tab{border:1px solid var(--border);border-radius:999px;padding:8px 12px;cursor:pointer;font-weight:600;background:#fff}
    .tab.active{background:#eaf1ff;border-color:#c7dcff;color:#1849a9}

    /* Table */
    .table-wrap{border:1px solid var(--border);border-radius:12px;overflow:hidden}
    table{width:100%;border-collapse:collapse;background:#fff}
    thead th{background:#f8fafc;border-bottom:1px solid var(--border);text-align:left;padding:10px;font-size:13px;color:#475569}
    tbody td{border-bottom:1px solid var(--border);padding:10px}
    tbody tr:hover{background:#f9fbff}
    .actions{display:flex;gap:8px;flex-wrap:wrap}

    /* Responsive */
    @media (max-width: 920px){
      .kpis{grid-template-columns:1fr}
    }
  </style>
</head>
<body>

<?php
// Datos de ejemplo (reemplaza con BD/sesión)
$empresaNombre = 'Casa del Barrio';

$activos = [
  ['nombre'=>'Ana Rodríguez','carrera'=>'Informática','periodo'=>'Feb–Jul 2025','proyecto'=>'Sistema Documental','estado'=>'En curso','detalle'=>'estudiante_view.php?id=101'],
  ['nombre'=>'Mario Díaz','carrera'=>'Industrial','periodo'=>'Feb–Jul 2025','proyecto'=>'Mejora de procesos','estado'=>'En curso','detalle'=>'estudiante_view.php?id=102'],
];
$historico = [
  ['nombre'=>'Juan Pérez','carrera'=>'Industrial','periodo'=>'Ago–Dic 2024','proyecto'=>'Optimización de línea','estado'=>'Concluido','detalle'=>'estudiante_view.php?id=77'],
  ['nombre'=>'Laura Méndez','carrera'=>'Informática','periodo'=>'Feb–Jul 2024','proyecto'=>'Inventario digital','estado'=>'Concluido','detalle'=>'estudiante_view.php?id=66'],
];

$kpiActivos    = count($activos);
$kpiConcluidos = count(array_filter($historico, fn($e)=>$e['estado']==='Concluido'));
$kpiCurso      = count(array_filter($activos, fn($e)=>$e['estado']==='En curso'));
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
      <p>Consulta tus residentes activos e histórico por periodo.</p>
    </div>
    <div class="actions">
      <a href="convenio_view.php" class="btn">📑 Ver convenio</a>
    </div>
  </section>

  <!-- KPIs -->
  <section class="card">
    <header>Resumen</header>
    <div class="content">
      <div class="kpis">
        <div class="kpi"><div class="num"><?= $kpiActivos ?></div><div class="lbl">Activos</div></div>
        <div class="kpi"><div class="num"><?= $kpiCurso ?></div><div class="lbl">En curso</div></div>
        <div class="kpi"><div class="num"><?= $kpiConcluidos ?></div><div class="lbl">Concluidos (histórico)</div></div>
      </div>
    </div>
  </section>

  <!-- Listados -->
  <section class="card">
    <header>Listado</header>
    <div class="content">

      <!-- Filtros -->
      <form class="filters" method="get" action="">
        <div class="field">
          <label for="q">Buscar</label>
          <input type="text" id="q" name="q" placeholder="Nombre, carrera o proyecto…">
        </div>
        <div class="field">
          <label for="periodo">Periodo</label>
          <select id="periodo" name="periodo">
            <option value="">Todos</option>
            <option>Feb–Jul 2025</option>
            <option>Ago–Dic 2024</option>
            <option>Feb–Jul 2024</option>
          </select>
        </div>
        <button class="btn primary" type="submit">🔎 Filtrar</button>
      </form>

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
              <th>Periodo</th>
              <th>Proyecto</th>
              <th>Estatus</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($activos as $e): ?>
            <tr>
              <td><?= htmlspecialchars($e['nombre']) ?></td>
              <td><?= htmlspecialchars($e['carrera']) ?></td>
              <td><?= htmlspecialchars($e['periodo']) ?></td>
              <td><?= htmlspecialchars($e['proyecto']) ?></td>
              <td><span class="badge info"><?= htmlspecialchars($e['estado']) ?></span></td>
              <td class="actions">
                <a class="btn small" href="<?= htmlspecialchars($e['detalle']) ?>">👁️ Ver detalle</a>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if(!$activos): ?>
            <tr><td colspan="6"><span class="hint">Sin residentes activos por ahora.</span></td></tr>
            <?php endif; ?>
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
              <th>Periodo</th>
              <th>Proyecto</th>
              <th>Resultado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($historico as $e): ?>
            <tr>
              <td><?= htmlspecialchars($e['nombre']) ?></td>
              <td><?= htmlspecialchars($e['carrera']) ?></td>
              <td><?= htmlspecialchars($e['periodo']) ?></td>
              <td><?= htmlspecialchars($e['proyecto']) ?></td>
              <td><span class="badge ok"><?= htmlspecialchars($e['estado']) ?></span></td>
              <td class="actions">
                <a class="btn small" href="<?= htmlspecialchars($e['detalle']) ?>">👁️ Ver detalle</a>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if(!$historico): ?>
            <tr><td colspan="6"><span class="hint">No hay histórico disponible.</span></td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
  </section>

</main>

<script>
  function showTab(which){
    const tabs = {activos:'tab-activos', historico:'tab-historico'};
    document.getElementById('tab-activos').style.display = (which==='activos')?'block':'none';
    document.getElementById('tab-historico').style.display = (which==='historico')?'block':'none';
    const buttons = document.querySelectorAll('.tab');
    buttons.forEach(b => b.classList.remove('active'));
    buttons[(which==='activos')?0:1].classList.add('active');
  }
</script>

</body>
</html>
