<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detalle del Documento · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/documentos/globaldocumentos.css" />
</head>
<body>

  <header>
    <h1>Detalle del Documento</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="ss_doc_list.php">Gestión de Documentos</a>
      <span>›</span>
      <span>Ver</span>
    </nav>
  </header>

  <main>
    <a href="ss_doc_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

    <!-- 📄 Información del documento -->
    <section class="card">
      <h2>Información General</h2>

      <dl class="details-list">
        <dt>ID del Documento:</dt>
        <dd>12</dd>

        <dt>Tipo de Documento:</dt>
        <dd>Reporte Bimestral</dd>

        <dt>Estudiante:</dt>
        <dd>Juan Carlos Pérez López (20214567)</dd>

        <dt>Periodo:</dt>
        <dd>Periodo 1 (Febrero - Julio 2025)</dd>

        <dt>Fecha de subida:</dt>
        <dd>2025-09-25</dd>

        <dt>Estado:</dt>
        <dd><span class="status pendiente">Pendiente</span></dd>

        <dt>Archivo:</dt>
        <dd>
          <a href="../../uploads/documentos/reporte_bimestral_juanperez.pdf" target="_blank" class="btn btn-primary">📄 Ver PDF</a>
        </dd>
      </dl>
    </section>

    <!-- 📝 Observaciones -->
    <section class="card">
      <h2>Observaciones</h2>
      <p>
        El documento fue entregado a tiempo, pero requiere revisión por parte del asesor interno. 
        Se recomienda verificar que el reporte contenga los apartados de conclusiones y anexos.
      </p>
    </section>

    <!-- 📊 Historial o registro de actividad -->
    <section class="card">
      <h2>Historial de Cambios</h2>
      <table class="styled-table">
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Acción</th>
            <th>Usuario</th>
            <th>Comentario</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>2025-09-25</td>
            <td>Documento subido</td>
            <td>Juan Carlos Pérez López</td>
            <td>Archivo inicial cargado</td>
          </tr>
          <tr>
            <td>2025-09-28</td>
            <td>Revisión solicitada</td>
            <td>Ing. Luis Martínez (SS Admin)</td>
            <td>Faltan conclusiones en el reporte</td>
          </tr>
        </tbody>
      </table>
    </section>

    <!-- ✅ Acciones -->
    <div class="actions">
      <a href="ss_doc_edit.php?id=12" class="btn btn-secondary">✏️ Editar Documento</a>
      <a href="ss_doc_delete.php?id=12" class="btn btn-danger">🗑️ Eliminar</a>
    </div>

  </main>

</body>
</html>
