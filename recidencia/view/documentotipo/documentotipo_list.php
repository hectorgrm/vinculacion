<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Tipos de Documento · Residencias</title>

  <link rel="stylesheet" href="../../assets/stylesrecidencia.css"/>
  <link rel="stylesheet" href="../../assets/css/documentotipo/documentotipo.css"/>
</head>
<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>📂 Tipos de Documento</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a><span>›</span>
            <span>Documento Tipo</span>
          </nav>
        </div>
        <a href="documentotipo_add.php" class="btn primary">➕ Nuevo Tipo</a>
      </header>

      <section class="card">
        <header>🔎 Filtros</header>
        <div class="content">
          <form class="form">
            <div class="filters">
              <div class="field">
                <label for="q">Buscar</label>
                <input id="q" name="q" type="text" placeholder="Clave, nombre o descripción">
              </div>
              <div class="field">
                <label for="estatus">Estatus</label>
                <select id="estatus" name="estatus">
                  <option value="">Todos</option>
                  <option value="activo">Activo</option>
                  <option value="inactivo">Inactivo</option>
                </select>
              </div>
              <div class="actions" style="margin:0">
                <button class="btn primary" type="submit">Buscar</button>
                <a class="btn" href="documentotipo_list.php">Limpiar</a>
              </div>
            </div>
          </form>
        </div>
      </section>

      <section class="card">
        <header>📋 Listado</header>
        <div class="content">
          <div class="table-wrapper">
            <table>
              <thead>
              <tr>
                <th>ID</th>
                <th>Clave</th>
                <th>Nombre</th>
                <th>Requiere Convenio</th>
                <th>Obligatorio</th>
                <th>Estatus</th>
                <th style="min-width:240px;">Acciones</th>
              </tr>
              </thead>
              <tbody>
              <!-- Ejemplos estáticos (luego dinámico) -->
              <tr>
                <td>1</td>
                <td>INE</td>
                <td>INE Representante</td>
                <td><span class="badge secondary">No</span></td>
                <td><span class="badge ok">Sí</span></td>
                <td><span class="badge ok">Activo</span></td>
                <td class="actions">
                  <a class="btn" href="documentotipo_edit.php?id=1">✏️ Editar</a>
                  <a class="btn danger" href="documentotipo_delete.php?id=1">🗑️ Eliminar</a>
                </td>
              </tr>
              <tr>
                <td>2</td>
                <td>ACTA</td>
                <td>Acta Constitutiva</td>
                <td><span class="badge secondary">No</span></td>
                <td><span class="badge ok">Sí</span></td>
                <td><span class="badge ok">Activo</span></td>
                <td class="actions">
                  <a class="btn" href="documentotipo_edit.php?id=2">✏️ Editar</a>
                  <a class="btn danger" href="documentotipo_delete.php?id=2">🗑️ Eliminar</a>
                </td>
              </tr>
              <tr>
                <td>3</td>
                <td>ANEXO</td>
                <td>Anexo Técnico</td>
                <td><span class="badge ok">Sí</span></td>
                <td><span class="badge secondary">No</span></td>
                <td><span class="badge warn">Inactivo</span></td>
                <td class="actions">
                  <a class="btn" href="documentotipo_edit.php?id=3">✏️ Editar</a>
                  <a class="btn danger" href="documentotipo_delete.php?id=3">🗑️ Eliminar</a>
                </td>
              </tr>
              </tbody>
            </table>
          </div>

          <div class="legend">
            <strong>Leyenda:</strong>
            <span class="badge ok">Sí / Activo</span>
            <span class="badge secondary">No</span>
            <span class="badge warn">Inactivo</span>
          </div>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
