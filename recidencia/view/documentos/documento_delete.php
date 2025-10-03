<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eliminar Documento - Residencia Profesional</title>
  <link rel="stylesheet" href="../../assets/css/documentos/documentoglobalstyles.css">
</head>
<body>

  <header>
    <h1>Eliminar Documento</h1>
    <nav class="breadcrumb">
      <a href="../dashboard.php">Inicio</a> <span>›</span>
      <a href="../empresas/empresa_list.php">Empresas</a> <span>›</span>
      <a href="../convenios/convenio_list.php">Convenios</a> <span>›</span>
      <a href="documento_list.php">Documentos</a> <span>›</span>
      <span>Eliminar</span>
    </nav>
  </header>

  <main>
    <div class="card danger-zone">
      <h2>⚠️ Confirmar Eliminación</h2>
      <p class="hint">Estás a punto de eliminar el siguiente documento del sistema. Esta acción es <strong>irreversible</strong>.</p>

      <!-- Información del documento -->
      <div class="document-info">
        <p><strong>ID:</strong> 8</p>
        <p><strong>Empresa:</strong> Casa del Barrio</p>
        <p><strong>Convenio:</strong> #1 (v1.2)</p>
        <p><strong>Tipo:</strong> Acta Constitutiva</p>
        <p><strong>Estatus:</strong> Aprobado</p>
        <p><strong>Archivo:</strong> <a href="/uploads/docs/technova_acta.pdf" target="_blank">📄 technova_acta.pdf</a></p>
      </div>

      <div class="alert alert-danger">
        ⚠️ Al eliminar este documento, no podrás recuperarlo.  
        Asegúrate de que realmente deseas realizar esta acción.
      </div>

      <form action="documento_delete_action.php" method="POST" class="form">
        <input type="hidden" name="id" value="8"><!-- 🔁 ID dinámico desde la BD -->

        <div class="form-actions">
          <button type="submit" class="btn btn-danger">🗑️ Eliminar Documento</button>
          <a href="documento_list.php" class="btn btn-secondary">Cancelar</a>
        </div>
      </form>
    </div>
  </main>

</body>
</html>
