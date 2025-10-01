<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Eliminar Documento · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/documentos/globaldocumentos.css" />
</head>
<body>

  <header class="danger-header">
    <h1>Eliminar Documento</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="ss_doc_list.php">Gestión de Documentos</a>
      <span>›</span>
      <span>Eliminar</span>
    </nav>
  </header>

  <main>
    <a href="ss_doc_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

    <section class="card form-card--danger">
      <h2>⚠️ Confirmar eliminación del documento</h2>
      <p>
        Estás a punto de <strong>eliminar definitivamente</strong> el siguiente documento.  
        Esta acción es <strong>irreversible</strong> y no podrás recuperarlo después de confirmar.
      </p>

      <!-- 📄 Información del documento a eliminar -->
      <div class="details-list">
        <dt>ID del Documento:</dt>
        <dd>15</dd>

        <dt>Estudiante:</dt>
        <dd>Juan Carlos Pérez López (20214567)</dd>

        <dt>Periodo:</dt>
        <dd>Periodo 2 - Mayo a Julio</dd>

        <dt>Tipo de Documento:</dt>
        <dd>Reporte Bimestral</dd>

        <dt>Estado actual:</dt>
        <dd><span class="status pendiente">Pendiente</span></dd>

        <dt>Fecha de subida:</dt>
        <dd>2025-09-15 10:42:00</dd>
      </div>

      <!-- ⚠️ Formulario de confirmación -->
      <form action="" method="post" class="form">
        <div class="field">
          <label for="confirmacion" class="required">Confirma escribiendo <strong>ELIMINAR</strong></label>
          <input type="text" id="confirmacion" name="confirmacion" placeholder="Escribe ELIMINAR para confirmar" required />
          <div class="hint">Por seguridad, debes escribir la palabra <strong>ELIMINAR</strong> para continuar.</div>
        </div>

        <div class="field">
          <label for="motivo">Motivo de eliminación (opcional)</label>
          <textarea id="motivo" name="motivo" placeholder="Describe brevemente el motivo de la eliminación..."></textarea>
        </div>

        <!-- ✅ Acciones -->
        <div class="actions">
          <a href="ss_doc_list.php" class="btn btn-secondary">Cancelar</a>
          <button type="submit" class="btn btn-danger">🗑️ Eliminar Documento</button>
        </div>
      </form>
    </section>
  </main>

</body>
</html>
