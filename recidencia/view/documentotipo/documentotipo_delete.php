<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Eliminar Tipo de Documento - Residencia Profesional</title>
  <link rel="stylesheet" href="../../assets/css/documentotipo/documentotipo_globalstyles.css" />
</head>
<body>

  <header>
    <h1>Eliminar Tipo de Documento</h1>
    <nav class="breadcrumb">
      <a href="../dashboard.php">Inicio</a> <span>›</span>
      <a href="../documentos/documento_list.php">Documentos</a> <span>›</span>
      <a href="documentotipo_list.php">Tipos de Documentos</a> <span>›</span>
      <span>Eliminar</span>
    </nav>
  </header>

  <main>
    <div class="card danger-zone">
      <h2>⚠️ Confirmar eliminación</h2>
      <p>Estás a punto de eliminar el siguiente tipo de documento del sistema. Esta acción <strong>no se puede deshacer</strong>.</p>

      <!-- 🧪 Ejemplo de datos cargados dinámicamente -->
      <div class="info">
        <p><strong>ID:</strong> 3</p>
        <p><strong>Nombre:</strong> Acta Constitutiva</p>
        <p><strong>Descripción:</strong> Documento legal que acredita la constitución de la empresa.</p>
        <p><strong>Obligatorio:</strong> Sí</p>
      </div>

      <form action="documentotipo_delete_action.php" method="POST" class="form">
        <!-- ID oculto del tipo de documento a eliminar -->
        <input type="hidden" name="id" value="3">

        <div class="form-actions" style="justify-content: space-between;">
          <a href="documentotipo_list.php" class="btn btn-secondary">Cancelar</a>
          <button type="submit" class="btn btn-danger">Eliminar Definitivamente</button>
        </div>
      </form>
    </div>
  </main>

</body>
</html>
