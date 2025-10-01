<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Documentos · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/documentos/globaldocumentos.css" />
</head>
<body>

  <header>
    <h1>Gestión de Documentos</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <span>Documentos</span>
    </nav>
  </header>

  <main>

    <!-- 🔍 Barra de búsqueda -->
    <div class="search-bar">
      <form action="" method="get" style="display:flex; gap:10px; align-items:center;">
        <input type="text" name="q" placeholder="Buscar por estudiante o tipo..." />
        <select name="estatus">
          <option value="">-- Filtrar por estado --</option>
          <option value="pendiente">Pendiente</option>
          <option value="aprobado">Aprobado</option>
          <option value="rechazado">Rechazado</option>
        </select>
        <button type="submit" class="btn btn-primary">🔎 Buscar</button>
      </form>

      <a href="ss_doc_add.php" class="btn btn-success">📤 Subir nuevo documento</a>
    </div>

    <!-- 📋 Lista de documentos -->
    <section class="card">
      <h2>Documentos Recibidos</h2>
      <p>Consulta todos los documentos subidos por los estudiantes o administradores del Servicio Social.</p>

      <table class="styled-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Estudiante</th>
            <th>Tipo de Documento</th>
            <th>Periodo</th>
            <th>Estado</th>
            <th>Fecha</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <!-- 📝 Ejemplos de datos (después se llenan dinámicamente con PHP) -->
          <tr>
            <td>1</td>
            <td>Juan Pérez López</td>
            <td>Reporte Bimestral</td>
            <td>Periodo 1</td>
            <td><span class="status pendiente">Pendiente</span></td>
            <td>2025-09-20</td>
            <td>
              <a href="ss_doc_view.php?id=1" class="btn btn-primary btn-sm">👁️ Ver</a>
              <a href="ss_doc_edit.php?id=1" class="btn btn-secondary btn-sm">✏️ Editar</a>
              <a href="ss_doc_delete.php?id=1" class="btn btn-danger btn-sm">🗑️ Eliminar</a>
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td>María López Torres</td>
            <td>Carta de Terminación</td>
            <td>Periodo 2</td>
            <td><span class="status aprobado">Aprobado</span></td>
            <td>2025-09-15</td>
            <td>
              <a href="ss_doc_view.php?id=2" class="btn btn-primary btn-sm">👁️ Ver</a>
              <a href="ss_doc_edit.php?id=2" class="btn btn-secondary btn-sm">✏️ Editar</a>
              <a href="ss_doc_delete.php?id=2" class="btn btn-danger btn-sm">🗑️ Eliminar</a>
            </td>
          </tr>
          <tr>
            <td>3</td>
            <td>José Ramírez</td>
            <td>Evaluación Cualitativa</td>
            <td>Periodo 1</td>
            <td><span class="status rechazado">Rechazado</span></td>
            <td>2025-09-10</td>
            <td>
              <a href="ss_doc_view.php?id=3" class="btn btn-primary btn-sm">👁️ Ver</a>
              <a href="ss_doc_edit.php?id=3" class="btn btn-secondary btn-sm">✏️ Editar</a>
              <a href="ss_doc_delete.php?id=3" class="btn btn-danger btn-sm">🗑️ Eliminar</a>
            </td>
          </tr>
        </tbody>
      </table>
    </section>

  </main>

</body>
</html>
