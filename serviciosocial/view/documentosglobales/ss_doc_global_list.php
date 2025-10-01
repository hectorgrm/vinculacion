<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Documentos Globales · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/documentos/documentosglobalstyles.css" />
</head>
<body>

  <header>
    <h1>Documentos Globales</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <span>Documentos Globales</span>
    </nav>
  </header>

  <main>
    <a href="ss_doc_global_add.php" class="btn btn-primary">➕ Subir Nuevo Documento Global</a>

    <section class="dg-card">
      <h2>Listado de Documentos Globales</h2>

      <div class="search-bar">
        <form action="" method="get">
          <input type="text" name="q" placeholder="Buscar por nombre o tipo..." />
          <button type="submit" class="btn btn-secondary">🔍 Buscar</button>
        </form>
      </div>

      <table class="styled-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Tipo</th>
            <th>Nombre del Archivo</th>
            <th>Fecha de Subida</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td><span class="doc-type">Carta de Presentación</span></td>
            <td>carta_presentacion.pdf</td>
            <td>2025-09-20</td>
            <td>
              <a href="#" class="file-link">📥 Descargar</a>
              <a href="ss_doc_global_view.php?id=1" class="btn btn-success">👁 Ver</a>
              <a href="ss_doc_global_edit.php?id=1" class="btn btn-primary">✏️ Editar</a>
              <a href="ss_doc_global_delete.php?id=1" class="btn btn-danger">🗑 Eliminar</a>
            </td>
          </tr>

          <tr>
            <td>2</td>
            <td><span class="doc-type">Instructivo General</span></td>
            <td>instructivo_servicio.pdf</td>
            <td>2025-09-25</td>
            <td>
              <a href="#" class="file-link">📥 Descargar</a>
              <a href="ss_doc_global_view.php?id=2" class="btn btn-success">👁 Ver</a>
              <a href="ss_doc_global_edit.php?id=2" class="btn btn-primary">✏️ Editar</a>
              <a href="ss_doc_global_delete.php?id=2" class="btn btn-danger">🗑 Eliminar</a>
            </td>
          </tr>
        </tbody>
      </table>
    </section>
  </main>

</body>
</html>
