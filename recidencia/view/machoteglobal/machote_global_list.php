<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>📚 Machote Global · Versiones Institucionales</title>

  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <!-- Si gustas, crea un CSS específico: ../../assets/css/machote/machote_global_list.css -->

  <style>
    :root{
      --bg:#f6f7fb; --card:#fff; --border:#e5e7eb; --text:#334155;
      --primary:#0d6efd; --success:#16a34a; --warn:#f59e0b; --muted:#64748b; --danger:#ef4444;
      --radius:12px;
    }
    body{background:var(--bg); color:var(--text)}
    .topbar{display:flex; justify-content:space-between; align-items:center; padding:16px 20px; background:#fff; border-bottom:1px solid var(--border)}
    .subtitle{margin:4px 0 0 0; color:#64748b}
    .actions{display:flex; gap:8px; flex-wrap:wrap}
    .btn{border:1px solid var(--border); background:#fff; padding:.5rem .85rem; border-radius:10px; cursor:pointer; text-decoration:none; color:inherit}
    .btn:hover{background:#f5f5f5}
    .btn.primary{background:var(--primary); color:#fff; border-color:var(--primary)}
    .btn.small{padding:.35rem .6rem; border-radius:8px; font-size:.9rem}
    .btn.danger{background:var(--danger); color:#fff; border-color:var(--danger)}

    main.main{padding:16px}
    .card{background:#fff; border:1px solid var(--border); border-radius:var(--radius); padding:14px}
    .filters{display:flex; gap:10px; align-items:center; flex-wrap:wrap; margin-bottom:12px}
    .filters input, .filters select{border:1px solid #cbd5e1; border-radius:10px; padding:8px; font-family:inherit}

    .table-wrapper{overflow:auto}
    table{width:100%; border-collapse:separate; border-spacing:0}
    thead th{background:#f1f5f9; text-align:left; padding:10px; font-size:.9rem; color:#475569; position:sticky; top:0}
    tbody td{padding:10px; border-top:1px solid var(--border); vertical-align:top}
    tbody tr:hover{background:#f8fafc}
    .badge{border-radius:999px; padding:2px 8px; font-weight:700; font-size:.75rem; color:#fff}
    .badge.vigente{background:#16a34a}
    .badge.borrador{background:#f59e0b}
    .badge.archivado{background:#64748b}
    .muted{color:#64748b; font-size:.9rem}
  </style>
</head>
<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <!-- Topbar -->
      <div class="topbar">
        <div>
          <h2>📚 Machote Global · Versiones Institucionales</h2>
          <p class="subtitle">Administra las versiones del machote institucional (plantilla base).</p>
        </div>
        <div class="actions">
          <a href="machote_edit.php" class="btn primary">➕ Nuevo Machote Global</a>
          <a href="machote_list.php" class="btn">🧩 Ir a Machotes por Empresa</a>
        </div>
      </div>

      <!-- Filtros -->
      <section class="card">
        <form class="filters" method="get" onsubmit="return false;">
          <input type="text" name="q" placeholder="Buscar por versión o descripción…" />
          <select name="estado">
            <option value="">Estado: Todos</option>
            <option value="vigente">Vigente</option>
            <option value="borrador">Borrador</option>
            <option value="archivado">Archivado</option>
          </select>
          <button class="btn primary">🔍 Buscar</button>
          <a class="btn" href="machote_global_list.php">Limpiar</a>
        </form>

        <!-- Tabla -->
        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th style="width:64px">#</th>
                <th>Versión</th>
                <th>Estado</th>
                <th>Descripción</th>
                <th>Creado</th>
                <th>Actualizado</th>
                <th style="min-width:280px">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <!-- FILA EJEMPLO 1 -->
              <tr>
                <td>1</td>
                <td><strong>Inst v1.3</strong></td>
                <td><span class="badge vigente">Vigente</span></td>
                <td>Cláusula de confidencialidad actualizada; formato de encabezado renovado.</td>
                <td>2025-11-01</td>
                <td>2025-11-08</td>
                <td>
                  <a href="machote_edit.php?version=1.3" class="btn small">✏️ Editar</a>
                  <a href="machote_revisar.php?version=1.3" class="btn small">🔍 Ver revisiones</a>
                  <button class="btn small" title="(Demo) Duplicar">📄 Duplicar</button>
                  <button class="btn small danger" title="(Demo) Archivar">🗄️ Archivar</button>
                </td>
              </tr>

              <!-- FILA EJEMPLO 2 -->
              <tr>
                <td>2</td>
                <td><strong>Inst v1.2</strong></td>
                <td><span class="badge archivado">Archivado</span></td>
                <td>Versión anterior (vigente hasta Oct-2025).</td>
                <td>2025-05-10</td>
                <td>2025-10-31</td>
                <td>
                  <button class="btn small" disabled>✏️ Editar</button>
                  <a href="machote_edit.php?version=1.2&read=1" class="btn small">👁️ Ver</a>
                  <button class="btn small" title="(Demo) Duplicar">📄 Duplicar</button>
                </td>
              </tr>

              <!-- FILA EJEMPLO 3 -->
              <tr>
                <td>3</td>
                <td><strong>Inst v1.4 (borrador)</strong></td>
                <td><span class="badge borrador">Borrador</span></td>
                <td>Ensayo de cláusulas para 2026; revisión legal en curso.</td>
                <td>2025-11-07</td>
                <td>2025-11-08</td>
                <td>
                  <a href="machote_edit.php?version=1.4" class="btn small">✏️ Editar</a>
                  <button class="btn small" title="(Demo) Activar como vigente">✅ Marcar vigente</button>
                  <button class="btn small danger" title="(Demo) Eliminar">🗑️ Eliminar</button>
                </td>
              </tr>

              <!-- Más filas… cuando conectes con BD (rp_machote) -->
            </tbody>
          </table>
        </div>

        <p class="muted" style="margin-top:8px">
          Esta vista se llenará con <code>rp_machote</code> (version, estado, descripcion, creado_en, actualizado_en).
          Las acciones se convertirán en endpoints cuando conectes el backend.
        </p>
      </section>
    </main>
  </div>

  <script>
    // Demo: puedes enganchar estos botones más tarde
    // document.querySelectorAll('button[title*="Demo"]').forEach(b=>b.addEventListener('click', ()=>alert('Acción demo UI')));
  </script>
</body>
</html>
