<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>📊 Reportes · Residencias Profesionales</title>

  <!-- Estilos globales -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css"/>

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Estilos mínimos específicos -->
  <style>
    .kpis{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
    .kpi{background:#fff;border:1px solid #e5e7eb;border-radius:18px;box-shadow:var(--shadow-sm);padding:16px}
    .kpi h3{margin:0 0 6px 0;font-size:14px;color:#64748b;font-weight:700}
    .kpi .num{font-size:28px;font-weight:800;color:#0f172a}
    .kpi small{color:#64748b}
    .charts{display:grid;grid-template-columns:1fr 1fr;gap:16px}
    .wide{grid-column:1 / -1}
    .badge{display:inline-block;padding:4px 8px;border-radius:999px;font-size:12px;font-weight:700;color:#fff}
    .badge.ok{background:#2db980}.badge.warn{background:#ffb400}.badge.err{background:#e44848}.badge.info{background:#1f6feb}
    .filters{display:flex;gap:12px;flex-wrap:wrap;align-items:end}
    .filters .field label{display:block;font-weight:700;margin-bottom:6px;color:#334155}
    .filters .field input,.filters .field select{padding:10px 12px;border:1px solid #e5e7eb;border-radius:10px}
    .table-wrapper{width:100%;overflow:auto}
    table{width:100%;border-collapse:collapse;background:#fff}
    thead tr{background:#f8fafc}
    th,td{border-bottom:1px solid #e5e7eb;padding:12px 14px;text-align:left}
    tbody tr:hover{background:#f1f5f9}
    @media (max-width:1100px){.charts{grid-template-columns:1fr}.kpis{grid-template-columns:repeat(2,1fr)}}
    @media (max-width:680px){.kpis{grid-template-columns:1fr}}
  </style>
</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <div>
          <h2>📊 Reportes y Estadísticas · Residencias</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a><span>›</span>
            <span>Reportes</span>
          </nav>
        </div>
        <div class="actions" style="gap:8px;flex-wrap:wrap">
          <button class="btn" onclick="window.print()">🖨️ Imprimir</button>
          <button class="btn">⬇️ Exportar CSV</button>
        </div>
      </header>

      <!-- Filtros -->
      <section class="card">
        <header>🔎 Filtros</header>
        <div class="content">
          <form class="filters">
            <div class="field">
              <label for="periodo">Periodo</label>
              <select id="periodo" name="periodo">
                <option value="actual">Actual (Feb–Jul 2025)</option>
                <option value="anterior">Ago–Dic 2024</option>
                <option value="todo">Todo</option>
              </select>
            </div>
            <div class="field">
              <label for="empresa">Empresa</label>
              <select id="empresa" name="empresa">
                <option value="">Todas</option>
                <option value="45">Casa del Barrio</option>
                <option value="22">Tequila ECT</option>
                <option value="31">Industrias Yakumo</option>
              </select>
            </div>
            <div class="field">
              <label for="estatus">Estatus de convenio</label>
              <select id="estatus" name="estatus">
                <option value="">Todos</option>
                <option value="vigente">Vigente</option>
                <option value="en_revision">En revisión</option>
                <option value="vencido">Vencido</option>
              </select>
            </div>
            <div class="actions" style="margin-left:auto">
              <button type="submit" class="btn primary">Aplicar</button>
              <button type="button" class="btn">Limpiar</button>
            </div>
          </form>
        </div>
      </section>

      <!-- KPIs -->
      <section class="kpis">
        <div class="kpi">
          <h3>🏢 Empresas activas</h3>
          <div class="num">42</div>
          <small>+5 vs periodo anterior</small>
        </div>
        <div class="kpi">
          <h3>📑 Convenios vigentes</h3>
          <div class="num">28</div>
          <small>3 por vencer</small>
        </div>
        <div class="kpi">
          <h3>📂 Docs validados</h3>
          <div class="num">1,236</div>
          <small>92% del total cargado</small>
        </div>
        <div class="kpi">
          <h3>🔐 Accesos activos</h3>
          <div class="num">37</div>
          <small>2 bloqueados</small>
        </div>
      </section>

      <!-- Gráficas -->
      <section class="charts">
        <div class="card">
          <header>📜 Convenios por estatus</header>
          <div class="content"><canvas id="chartConvenios"></canvas></div>
        </div>

        <div class="card">
          <header>📄 Documentos por tipo</header>
          <div class="content"><canvas id="chartDocsTipo"></canvas></div>
        </div>

        <div class="card wide">
          <header>🔐 Actividad de accesos (últimos 6 meses)</header>
          <div class="content"><canvas id="chartAccesos"></canvas></div>
        </div>
      </section>

      <!-- Tablas resumen -->
      <section class="charts">
        <div class="card">
          <header>🏆 Top 5 empresas con más residentes</header>
          <div class="content table-wrapper">
            <table>
              <thead>
                <tr><th>#</th><th>Empresa</th><th>Residentes</th><th>Convenio</th></tr>
              </thead>
              <tbody>
                <tr><td>1</td><td>Secretaría de Innovación</td><td>18</td><td><span class="badge ok">Vigente</span></td></tr>
                <tr><td>2</td><td>Municipio de Guadalajara</td><td>15</td><td><span class="badge ok">Vigente</span></td></tr>
                <tr><td>3</td><td>Hospital Civil</td><td>12</td><td><span class="badge warn">Por vencer</span></td></tr>
                <tr><td>4</td><td>Industrias Yakumo</td><td>11</td><td><span class="badge ok">Vigente</span></td></tr>
                <tr><td>5</td><td>Tequila ECT</td><td>9</td><td><span class="badge info">En revisión</span></td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="card">
          <header>⏳ Documentos pendientes de validación</header>
          <div class="content table-wrapper">
            <table>
              <thead>
                <tr><th>Empresa</th><th>Tipo</th><th>Subido</th><th>Estatus</th></tr>
              </thead>
              <tbody>
                <tr><td>Casa del Barrio</td><td>Acta Constitutiva</td><td>2025-09-28</td><td><span class="badge info">En revisión</span></td></tr>
                <tr><td>Tequila ECT</td><td>Anexo Técnico</td><td>2025-10-01</td><td><span class="badge info">En revisión</span></td></tr>
                <tr><td>Industrias Yakumo</td><td>Poder Notarial</td><td>2025-09-15</td><td><span class="badge err">Rechazado</span></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>
    </main>
  </div>

  <!-- Configuración de gráficas -->
  <script>
    // Convenios por estatus
    new Chart(document.getElementById('chartConvenios'),{
      type:'doughnut',
      data:{
        labels:['Vigente','En revisión','Vencido','Por vencer'],
        datasets:[{ data:[28,6,3,3], backgroundColor:['#2db980','#1f6feb','#e44848','#ffb400'] }]
      },
      options:{ responsive:true, plugins:{legend:{position:'bottom'}} }
    });

    // Documentos por tipo
    new Chart(document.getElementById('chartDocsTipo'),{
      type:'bar',
      data:{
        labels:['INE','Acta Constitutiva','Anexo Técnico','Poder Notarial','Carta Compromiso'],
        datasets:[{ label:'Cargados', data:[260,210,180,95,120], backgroundColor:'#1f6feb' }]
      },
      options:{ responsive:true, scales:{ y:{ beginAtZero:true } } }
    });

    // Actividad de accesos (últimos 6 meses)
    new Chart(document.getElementById('chartAccesos'),{
      type:'line',
      data:{
        labels:['Mayo','Jun','Jul','Ago','Sep','Oct'],
        datasets:[
          { label:'Inicios de sesión', data:[120,150,132,178,165,190], borderColor:'#1f6feb', tension:.2 },
          { label:'Intentos fallidos', data:[18,22,15,28,24,19], borderColor:'#e44848', tension:.2 }
        ]
      },
      options:{ responsive:true, plugins:{legend:{position:'bottom'}}, scales:{ y:{ beginAtZero:true } } }
    });
  </script>
</body>
</html>
