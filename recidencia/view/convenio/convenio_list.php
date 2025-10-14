<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Convenios · Residencias Profesionales</title>

  <!-- Estilos globales del módulo -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/convenios/convenio_list.css" />

  <!-- (Opcional) Estilos específicos para lista de convenios -->
  <!-- <link rel="stylesheet" href="../../assets/css/residencias/convenio_list.css" /> -->
</head>

<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <div>
          <h2>📑 Gestión de Convenios</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>›</span>
            <span>Convenios</span>
          </nav>
        </div>
        <a href="convenio_add.php" class="btn primary">➕ Nuevo Convenio</a>
      </header>

      <!-- (Opcional) Aviso contextual cuando se filtra por empresa
      <section class="card">
        <header>📌 Contexto</header>
        <div class="content">
          Mostrando convenios de la empresa <strong>Casa del Barrio</strong> (ID #45).
          <div class="actions" style="margin-top:10px;">
            <a class="btn" href="../empresa/empresa_view.php?id=45">🏢 Ir a la empresa</a>
            <a class="btn" href="convenio_add.php?empresa=45">➕ Nuevo convenio para esta empresa</a>
          </div>
        </div>
      </section>
      -->

      <section class="card">
        <header>📋 Listado de Convenios</header>
        <div class="content">
          <!-- BUSCADOR + FILTROS -->
          <form method="GET" class="form" style="margin-bottom: 14px;">
            <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
              <div class="field" style="min-width:260px; max-width:360px; flex:1;">
                <label for="search">Buscar</label>
                <input type="text" id="search" name="search" placeholder="Empresa, versión o notas..." />
              </div>

              <div class="field">
                <label for="estatus">Estatus</label>
                <select id="estatus" name="estatus">
                  <option value="">Todos</option>
                  <option value="vigente">Vigente</option>
                  <option value="en_revision">En revisión</option>
                  <option value="pendiente">Pendiente</option>
                  <option value="vencido">Vencido</option>
                </select>
              </div>

              <div class="actions" style="margin:0;">
                <button type="submit" class="btn primary">🔎 Buscar</button>
                <a href="convenio_list.php" class="btn">Limpiar</a>
              </div>
            </div>
          </form>

          <!-- TABLA -->
          <div class="table-wrapper" style="overflow:auto;">
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Empresa</th>
                  <th>Estatus</th>
                  <th>Fecha Inicio</th>
                  <th>Fecha Fin</th>
                  <th>Versión</th>
                  <th style="min-width:220px;">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <!-- Ejemplos estáticos (luego dinámico con PHP) -->
                <tr>
                  <td>1</td>
                  <td>Casa del Barrio</td>
                  <td><span class="badge ok">Vigente</span></td>
                  <td>2025-07-01</td>
                  <td>2026-06-30</td>
                  <td>v1.2</td>
                  <td class="actions">
                    <a href="convenio_view.php?id=1" class="btn">👁️ Ver</a>
                    <a href="convenio_edit.php?id=1" class="btn">✏️ Editar</a>
                    <a href="convenio_delete.php?id=1" class="btn">🗑️ Eliminar</a>
                  </td>
                </tr>

                <tr>
                  <td>2</td>
                  <td>Tequila ECT</td>
                  <td><span class="badge secondary">En revisión</span></td>
                  <td>2025-08-15</td>
                  <td>—</td>
                  <td>v1.0</td>
                  <td class="actions">
                    <a href="convenio_view.php?id=2" class="btn">👁️ Ver</a>
                    <a href="convenio_edit.php?id=2" class="btn">✏️ Editar</a>
                    <a href="convenio_delete.php?id=2" class="btn">🗑️ Eliminar</a>
                  </td>
                </tr>

                <tr>
                  <td>3</td>
                  <td>Industrias Yakumo</td>
                  <td><span class="badge warn">Pendiente</span></td>
                  <td>—</td>
                  <td>—</td>
                  <td>—</td>
                  <td class="actions">
                    <a href="convenio_view.php?id=3" class="btn">👁️ Ver</a>
                    <a href="convenio_edit.php?id=3" class="btn">✏️ Editar</a>
                    <a href="convenio_delete.php?id=3" class="btn">🗑️ Eliminar</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Leyenda de estatus -->
          <div style="margin-top:10px; color:#64748b; font-size:14px;">
            <strong>Leyenda:</strong>
            <span class="badge ok">✅ Vigente</span>
            <span class="badge secondary">🕓 En revisión</span>
            <span class="badge warn">⚠️ Pendiente</span>
            <span class="badge err">⛔ Vencido</span>

          </div>
        </div>
      </section>
    </main>
  </div>
</body>

</html>