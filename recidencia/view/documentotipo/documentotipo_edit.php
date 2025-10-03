<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Tipo de Documento - Residencia Profesional</title>
  <link rel="stylesheet" href="../../assets/css/documentotipo/documentotipo_globalstyles.css" />
</head>
<body>

  <header>
    <h1>Editar Tipo de Documento</h1>
    <nav class="breadcrumb">
      <a href="../dashboard.php">Inicio</a> <span>›</span>
      <a href="../documentos/documento_list.php">Documentos</a> <span>›</span>
      <a href="documentotipo_list.php">Tipos de Documentos</a> <span>›</span>
      <span>Editar</span>
    </nav>
  </header>

  <main>
    <div class="card form-container">
      <h2>Actualizar Información del Tipo de Documento</h2>
      <p>Edita los campos necesarios y guarda los cambios.</p>

      <!-- ⚙️ Nota: El ID del documento se envía oculto para que el backend sepa cuál actualizar -->
      <form action="documentotipo_edit_action.php" method="POST" class="form">
        <input type="hidden" name="id" value="3" /> <!-- Este valor vendrá dinámico desde la BD -->

        <!-- Nombre -->
        <div class="field">
          <label for="nombre">Nombre del documento <span style="color:red">*</span></label>
          <input 
            type="text" 
            id="nombre" 
            name="nombre" 
            value="Acta Constitutiva" 
            placeholder="Ej: Acta Constitutiva" 
            required 
          />
        </div>

        <!-- Descripción -->
        <div class="field">
          <label for="descripcion">Descripción</label>
          <textarea 
            id="descripcion" 
            name="descripcion" 
            rows="4" 
            placeholder="Describe brevemente el propósito del documento..."
          >Documento legal que acredita la constitución oficial de la empresa.</textarea>
        </div>

        <!-- Obligatorio -->
        <div class="field">
          <label for="obligatorio">¿Es obligatorio?</label>
          <select name="obligatorio" id="obligatorio" required>
            <option value="1" selected>Sí, es obligatorio</option>
            <option value="0">No, es opcional</option>
          </select>
        </div>

        <!-- Botones -->
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
          <a href="documentotipo_list.php" class="btn btn-secondary">Cancelar</a>
        </div>

      </form>
    </div>
  </main>

</body>
</html>
