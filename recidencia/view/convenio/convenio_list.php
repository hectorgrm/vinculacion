<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Convenios · Residencias Profesionales</title>
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/convenios/convenio_list.css" />
</head>
<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>📑 Gestión de Convenios</h2>
          <p class="subtitle">Controla los convenios activos, en revisión y vencidos con las empresas vinculadas.</p>
        </div>
        <a href="convenio_add.php" class="btn primary">➕ Nuevo Convenio</a>
      </header>

      <!-- FILTROS -->
      <section class="card">
        <header>🔍 Filtros y búsqueda</header>
        <div class="content">
          <form method="GET" class="form search-bar">
            <input type="text" name="search" placeholder="Buscar por empresa, folio o versión..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" />
            <select name="estatus">
              <option value="">Todos los estados</option>
              <option value="Activa">Activa</option>
              <option value="En revisión">En revisión</option>
              <option value="Inactiva">Inactiva</option>
              <option value="Suspendida">Suspendida</option>
            </select>
            <button type="submit" class="btn primary">Buscar</button>
            <a href="convenio_list.php" class="btn secondary">Limpiar</a>
          </form>
        </div>
      </section>

      <!-- LISTA -->
      <section class="card">
        <header>📋 Convenios registrados</header>
        <div class="content">
          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Empresa</th>
                  <th>Folio</th>
                  <th>Estatus</th>
                  <th>Machote</th>
                  <th>Versión</th>
                  <th>Inicio</th>
                  <th>Fin</th>
                  <th>Actualizado</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <!-- Ejemplo estático -->
                <tr>
                  <td>1</td>
                  <td>Casa del Barrio</td>
                  <td>CBR-2025-01</td>
                  <td><span class="badge ok">Activa</span></td>
                  <td>v1.2</td>
                  <td>v1.0</td>
                  <td>2025-07-01</td>
                  <td>2026-06-30</td>
                  <td>2025-09-09</td>
                  <td class="actions">
                    <a href="convenio_view.php?id=1" class="btn sm">👁️</a>
                    <a href="convenio_edit.php?id=1" class="btn sm">✏️</a>
                    <a href="convenio_delete.php?id=1" class="btn sm">🗑️</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- LEYENDA -->
      <div class="legend">
        <span class="badge ok">Activa</span>
        <span class="badge secondary">En revisión</span>
        <span class="badge warn">Inactiva</span>
        <span class="badge err">Suspendida</span>
      </div>
    </main>
  </div>
</body>
</html>
