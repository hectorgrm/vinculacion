<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Portal de Acceso · Residencias Profesionales</title>

  <!-- Estilos globales -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />

  <style>
    :root{
      --ok:#16a34a;
      --warn:#f59e0b;
      --err:#e11d48;
      --secondary:#64748b;
      --border:#e5e7eb;
      --bg:#fff;
      --shadow:0 4px 14px rgba(0,0,0,.04);
    }
    body{font-family:Inter,system-ui,sans-serif;margin:0;background:#f6f8fb;color:#0f172a}
    .badge{display:inline-block;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:700;color:#fff}
    .badge.ok{background:var(--ok)}
    .badge.warn{background:var(--warn)}
    .badge.err{background:var(--err)}
    .badge.secondary{background:var(--secondary)}
    .btn{display:inline-block;padding:8px 12px;border-radius:8px;text-decoration:none;border:1px solid var(--border);color:#0f172a;background:#fff}
    .btn.primary{background:#1f6feb;color:#fff;border:none}
    .btn.danger{background:#e11d48;color:#fff;border:none}
    .card{background:#fff;border:1px solid var(--border);border-radius:12px;box-shadow:var(--shadow);margin-bottom:20px}
    .card header{padding:12px 16px;border-bottom:1px solid var(--border);font-weight:700;font-size:16px}
    .card .content{padding:16px}
    table{width:100%;border-collapse:collapse;font-size:14px}
    th,td{padding:10px 8px;border-bottom:1px solid var(--border);text-align:left}
    th{background:#f1f5f9;text-transform:uppercase;font-size:13px;color:#475569}
    .actions .btn{font-size:13px;padding:6px 10px}
    .topbar{display:flex;justify-content:space-between;align-items:center;padding:14px 18px;border-bottom:1px solid var(--border);background:#fff}
    .breadcrumb{font-size:13px;color:#64748b}
    .breadcrumb a{text-decoration:none;color:#1f6feb}
    .main{padding:18px}
    .legend{margin-top:10px;color:#64748b;font-size:13px;display:flex;gap:10px;flex-wrap:wrap;align-items:center}
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
          <h2>🔐 Portales de Acceso</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a> › Portal de Acceso
          </nav>
        </div>
        <a class="btn primary" href="portal_add.php">➕ Crear acceso</a>
      </header>

      <!-- Listado -->
      <section class="card">
        <header>📋 Accesos Registrados</header>
        <div class="content">
          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Empresa</th>
                  <th>Token</th>
                  <th>NIP</th>
                  <th>Estatus</th>
                  <th>Expira</th>
                  <th>Creado en</th>
                  <th style="min-width:250px;">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <!-- Ejemplo estático -->
                <tr>
                  <td>4</td>
                  <td><a class="btn" href="../empresa/empresa_view.php?id=4">Casa del Barrio</a></td>
                  <td><code>d2613fc5-00c6-4c3f-9761-91d6100ebf10</code></td>
                  <td>202020</td>
                  <td><span class="badge ok">Activo</span></td>
                  <td>2025-11-07 02:58</td>
                  <td>2025-11-03 02:59</td>
                  <td class="actions" style="display:flex;gap:6px;flex-wrap:wrap;">
                    <a class="btn" href="portal_view.php?id=4">👁️ Ver</a>
                    <a class="btn" href="portal_edit.php?id=4">✏️ Editar</a>
                    <a class="btn" href="portal_toggle.php?id=4&to=0">🚫 Desactivar</a>
                    <a class="btn danger" href="portal_delete.php?id=4">🗑️ Eliminar</a>
                  </td>
                </tr>
                <tr>
                  <td>5</td>
                  <td><a class="btn" href="../empresa/empresa_view.php?id=5">Tequila ECT</a></td>
                  <td><code>7a12b2d0-712d-40f2-9cc5-22e93a1fa530</code></td>
                  <td>909090</td>
                  <td><span class="badge warn">Inactivo</span></td>
                  <td>2025-11-12 00:00</td>
                  <td>2025-11-01 08:10</td>
                  <td class="actions" style="display:flex;gap:6px;flex-wrap:wrap;">
                    <a class="btn" href="portal_view.php?id=5">👁️ Ver</a>
                    <a class="btn" href="portal_edit.php?id=5">✏️ Editar</a>
                    <a class="btn" href="portal_toggle.php?id=5&to=1">✅ Activar</a>
                    <a class="btn danger" href="portal_delete.php?id=5">🗑️ Eliminar</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Leyenda -->
          <div class="legend">
            <strong>Leyenda:</strong>
            <span class="badge ok">Activo</span>
            <span class="badge warn">Inactivo</span>
            <span class="badge err">Expirado</span>
          </div>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
