<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal Empresa · Documentos</title>

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
    .badge.danger{background:#fee2e2;color:#991b1b;border:1px solid #fecaca}
    .hint{color:var(--muted);font-size:12px}

    .titlebar{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:14px}
    .titlebar h1{margin:0 0 4px 0;font-size:22px}
    .titlebar p{margin:0;color:var(--muted)}

    .grid{display:grid;grid-template-columns:1fr .9fr;gap:16px}
    @media (max-width: 1024px){.grid{grid-template-columns:1fr}}

    .card{background:var(--panel);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow)}
    .card header{padding:12px 14px;border-bottom:1px solid var(--border);font-weight:700}
    .card .content{padding:14px}

    /* KPIs */
    .kpis{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
    .kpi{background:#f8fafc;border:1px solid var(--border);border-radius:12px;padding:12px;text-align:center}
    .kpi .num{font-size:22px;font-weight:800}
    .kpi .lbl{font-size:12px;color:var(--muted)}

    /* Filters */
    .filters{display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;margin-bottom:10px}
    .filters .field{display:flex;flex-direction:column;gap:6px}
    .filters input, .filters select{height:38px;padding:8px 10px;border:1px solid var(--border);border-radius:10px}
    .filters label{font-size:13px;color:#475569}

    /* Table */
    .table-wrap{border:1px solid var(--border);border-radius:12px;overflow:hidden}
    table{width:100%;border-collapse:collapse;background:#fff}
    thead th{background:#f8fafc;border-bottom:1px solid var(--border);text-align:left;padding:10px;font-size:13px;color:#475569}
    tbody td{border-bottom:1px solid var(--border);padding:10px}
    tbody tr:hover{background:#f9fbff}
    .actions{display:flex;gap:8px;flex-wrap:wrap}

    /* Upload block (optional) */
    .upload{display:grid;grid-template-columns:1fr;gap:10px}
    .upload .row{display:grid;grid-template-columns:1fr 1fr;gap:10px}
    @media (max-width:720px){.upload .row{grid-template-columns:1fr}}
    .upload input[type="file"], .upload select{padding:10px;border:1px solid var(--border);border-radius:10px}
    .upload textarea{padding:10px;border:1px solid var(--border);border-radius:10px;resize:vertical}
  </style>
</head>
<body>

<?php
// Datos de ejemplo (reemplaza con BD/sesión)
$empresaNombre = 'Casa del Barrio';
$docs = [
  ['nombre'=>'INE Representante','estado'=>'Aprobado','fecha'=>'2025-09-10','archivo'=>'/uploads/ine_representante.pdf','obs'=>null],
  ['nombre'=>'Acta Constitutiva','estado'=>'Pendiente','fecha'=>'—','archivo'=>null,'obs'=>'Falta sello legible'],
  ['nombre'=>'Poder Notarial','estado'=>'Rechazado','fecha'=>'2025-09-01','archivo'=>'/uploads/poder_notarial.pdf','obs'=>'Página 2 borrosa'],
  ['nombre'=>'Comprobante Domicilio','estado'=>'Aprobado','fecha'=>'2025-09-05','archivo'=>'/uploads/comp_domicilio.pdf','obs'=>null],
];
$kpiOk   = count(array_filter($docs, fn($d)=>$d['estado']==='Aprobado'));
$kpiPend = count(array_filter($docs, fn($d)=>$d['estado']==='Pendiente'));
$kpiRech = count(array_filter($docs, fn($d)=>$d['estado']==='Rechazado'));
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
      <h1>Documentos de la empresa</h1>
      <p>Consulta el estado de tus documentos y descarga los que ya están aprobados.</p>
    </div>
    <div class="actions">
      <a href="convenio_view.php" class="btn">📑 Ver convenio</a>
    </div>
  </section>

  <section class="grid">
    <!-- Columna izquierda: KPIs + Tabla -->
    <div class="col">
      <div class="card">
        <header>Resumen</header>
        <div class="content">
          <div class="kpis">
            <div class="kpi"><div class="num"><?= $kpiOk ?></div><div class="lbl">Aprobados</div></div>
            <div class="kpi"><div class="num"><?= $kpiPend ?></div><div class="lbl">Pendientes</div></div>
            <div class="kpi"><div class="num"><?= $kpiRech ?></div><div class="lbl">Rechazados</div></div>
          </div>
        </div>
      </div>

      <div class="card">
        <header>Listado</header>
        <div class="content">

          <!-- Filtros -->
          <form class="filters" method="get" action="">
            <div class="field">
              <label for="q">Buscar</label>
              <input type="text" id="q" name="q" placeholder="Nombre del documento…">
            </div>
            <div class="field">
              <label for="estado">Estado</label>
              <select id="estado" name="estado">
                <option value="">Todos</option>
                <option value="Aprobado">Aprobado</option>
                <option value="Pendiente">Pendiente</option>
                <option value="Rechazado">Rechazado</option>
              </select>
            </div>
            <button class="btn primary" type="submit">🔎 Filtrar</button>
          </form>

          <!-- Tabla -->
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Documento</th>
                  <th>Estado</th>
                  <th>Fecha</th>
                  <th>Observaciones</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($docs as $d): ?>
                <tr>
                  <td><?= htmlspecialchars($d['nombre']) ?></td>
                  <td>
                    <?php if($d['estado']==='Aprobado'): ?>
                      <span class="badge ok">Aprobado</span>
                    <?php elseif($d['estado']==='Pendiente'): ?>
                      <span class="badge warn">Pendiente</span>
                    <?php else: ?>
                      <span class="badge danger">Rechazado</span>
                    <?php endif; ?>
                  </td>
                  <td><?= htmlspecialchars($d['fecha']) ?></td>
                  <td><?= $d['obs'] ? htmlspecialchars($d['obs']) : '—' ?></td>
                  <td class="actions">
                    <?php if($d['archivo']): ?>
                      <a class="btn small" href="<?= htmlspecialchars($d['archivo']) ?>" target="_blank">📄 Ver</a>
                      <a class="btn small" href="<?= htmlspecialchars($d['archivo']) ?>" download>⬇️ Descargar</a>
                    <?php else: ?>
                      <span class="hint">Sin archivo</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>

    <!-- Columna derecha: Subir/Actualizar (opcional) -->
    <div class="col">
      <div class="card" id="subir">
        <header>Subir / Actualizar documento</header>
        <div class="content">
          <p class="hint">Usa este formulario solo si Residencias te indicó reemplazar un documento pendiente o rechazado.</p>
          <form class="upload" method="post" action="documento_upload_action.php" enctype="multipart/form-data">
            <div class="row">
              <div class="field">
                <label for="doc_tipo">Tipo de documento</label>
                <select id="doc_tipo" name="doc_tipo" required>
                  <option value="">Selecciona…</option>
                  <option>INE Representante</option>
                  <option>Acta Constitutiva</option>
                  <option>Poder Notarial</option>
                  <option>Comprobante Domicilio</option>
                </select>
              </div>
              <div class="field">
                <label for="archivo">Archivo (PDF/JPG/PNG)</label>
                <input type="file" id="archivo" name="archivo" accept=".pdf,.jpg,.jpeg,.png" required>
              </div>
            </div>

            <div class="field">
              <label for="comentario">Comentario (opcional)</label>
              <textarea id="comentario" name="comentario" rows="3" placeholder="Notas para el área de Residencias…"></textarea>
            </div>

            <div class="actions">
              <button class="btn primary" type="submit">⬆️ Subir documento</button>
              <a class="btn" href="#top">Cancelar</a>
            </div>
          </form>
        </div>
      </div>

      <div class="card">
        <header>Ayuda</header>
        <div class="content">
          <ul>
            <li>Formatos aceptados: PDF, JPG, PNG.</li>
            <li>Tamaño máximo recomendado: 10 MB.</li>
            <li>Si un documento fue rechazado, revisa la observación antes de subir de nuevo.</li>
          </ul>
        </div>
      </div>

    </div>
  </section>

</main>

</body>
</html>
