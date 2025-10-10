<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Empresas · Residencia Profesional</title>

  <!-- Estilos globales del módulo de Residencias -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
    <link rel="stylesheet" href="../../assets/css/empresas/empresalist.css">

</head>
<body>
  <div class="app">
    <!-- Sidebar (usa el que ya configuraste con tus rutas) -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <div>
          <h2>Empresas · Residencia Profesional</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>›</span>
            <span>Empresas</span>
          </nav>
        </div>
        <a href="empresa_add.php" class="btn primary">➕ Registrar Nueva Empresa</a>
      </header>

      <section class="card">
        <header>📂 Lista de Empresas Registradas</header>
        <div class="content">
          <!-- BUSCADOR -->
          <form class="form" style="margin: 0 0 16px 0;">
            <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
              <div class="field" style="min-width:260px; max-width:360px; flex:1;">
                <label for="search">Buscar empresa:</label>
                <input type="text" id="search" name="search" placeholder="Nombre, contacto o RFC..." />
              </div>
              <div class="actions" style="margin:0;">
                <button type="submit" class="btn primary">🔍 Buscar</button>
                <a href="empresa_list.php" class="btn">Limpiar</a>
              </div>
            </div>
          </form>

          <!-- TABLA DE EMPRESAS -->
          <div class="table-wrapper" style="overflow:auto;">
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre</th>
                  <th>RFC</th>
                  <th>Contacto</th>
                  <th>Email</th>
                  <th>Teléfono</th>
                  <th>Estatus</th>
                  <th style="width:280px;">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <!-- 🧪 Registros de ejemplo (remplazar por PHP dinámico) -->
                <tr>
                  <td>1</td>
                  <td>Casa del Barrio</td>
                  <td>CDB810101AA1</td>
                  <td>José Manuel Velador</td>
                  <td>contacto@casadelbarrio.mx</td>
                  <td>(33) 1234 5678</td>
                  <td><span class="badge ok">Activa</span></td>
                  <td class="actions" style="display:flex; gap:8px; flex-wrap:wrap;">
                    <a href="empresa_view.php?id=1" class="btn">👁️ Ver</a>
                    <a href="empresa_edit.php?id=1" class="btn">✏️ Editar</a>
                    <a href="empresa_delete.php?id=1" class="btn">🗑️ Eliminar</a>
                  </td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>Tequila ECT</td>
                  <td>TEC920202BB2</td>
                  <td>María González</td>
                  <td>legal@tequilaect.com</td>
                  <td>(33) 2345 6789</td>
                  <td><span class="badge ok">Activa</span></td>
                  <td class="actions" style="display:flex; gap:8px; flex-wrap:wrap;">
                    <a href="empresa_view.php?id=2" class="btn">👁️ Ver</a>
                    <a href="empresa_edit.php?id=2" class="btn">✏️ Editar</a>
                    <a href="empresa_delete.php?id=2" class="btn">🗑️ Eliminar</a>
                  </td>
                </tr>
                <tr>
                  <td>3</td>
                  <td>Industrias Yakumo</td>
                  <td>IYA930303CC3</td>
                  <td>Luis Pérez</td>
                  <td>vinculacion@yakumo.com</td>
                  <td>(55) 3456 7890</td>
                  <td><span class="badge ok">Activa</span></td>
                  <td class="actions" style="display:flex; gap:8px; flex-wrap:wrap;">
                    <a href="empresa_view.php?id=3" class="btn">👁️ Ver</a>
                    <a href="empresa_edit.php?id=3" class="btn">✏️ Editar</a>
                    <a href="empresa_delete.php?id=3" class="btn">🗑️ Eliminar</a>
                  </td>
                </tr>
                <!-- /Registros de ejemplo -->
              </tbody>
            </table>
          </div>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
