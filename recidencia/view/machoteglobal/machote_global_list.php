<?php
if (!isset($machotes)) {
    require __DIR__ . '/../../handler/machoteglobal/machote_global_list_handler.php';
    return;
}
?>
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
                <th style="min-width:140px">Vista</th>
                <th style="min-width:280px">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($machotes)) : ?>
                <tr>
                  <td colspan="7" style="text-align:center; padding:20px;" class="muted">
                    No se encontraron machotes registrados.
                  </td>
                </tr>
              <?php else : ?>
                <?php foreach ($machotes as $index => $machote) :
                    $id = (int)($machote['id'] ?? 0);
                    $version = htmlspecialchars((string)($machote['version'] ?? ''), ENT_QUOTES, 'UTF-8');
                    $estado = (string)($machote['estado'] ?? 'borrador');
                    $descripcion = trim((string)($machote['descripcion'] ?? ''));
                    $descripcion = $descripcion !== ''
                        ? nl2br(htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8'))
                        : '<span class="muted">Sin descripción</span>';
                    $creado = machote_global_format_datetime($machote['creado_en'] ?? null);
                    $actualizado = machote_global_format_datetime($machote['actualizado_en'] ?? null);
                    $badgeClass = machote_global_estado_badge($estado);
                    $estadoLabel = machote_global_estado_label($estado);
                ?>
                  <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><strong><?php echo $version !== '' ? $version : '—'; ?></strong></td>
                    <td><span class="<?php echo $badgeClass; ?>"><?php echo $estadoLabel; ?></span></td>
                    <td><?php echo $descripcion; ?></td>
                    <td><?php echo $creado; ?></td>
                    <td><?php echo $actualizado; ?></td>
                    <td>
                      <a
                        href="../../view/machoteglobal/machote_view.php?id=<?php echo $id; ?>"
                        class="btn small"
                        title="Ver machote #<?php echo $id; ?> en modo lectura"
                      >👁️ Ver</a>
                    </td>
                    <td>
                      <a href="machote_edit.php?id=<?php echo $id; ?>" class="btn small">✏️ Editar</a>
                      <a href="machote_revisar.php?id=<?php echo $id; ?>" class="btn small">🔍 Ver revisiones</a>
                      <button class="btn small" type="button" title="Duplicar registro #<?php echo $id; ?>">📄 Duplicar</button>
                      <button class="btn small danger" type="button" title="Archivar registro #<?php echo $id; ?>">🗄️ Archivar</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <p class="muted" style="margin-top:8px">
          Información obtenida de <code>rp_machote</code> (versión, estado, descripción, creado_en, actualizado_en).
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
