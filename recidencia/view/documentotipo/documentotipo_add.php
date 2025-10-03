<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Agregar Tipo de Documento - Residencia Profesional</title>
  <link rel="stylesheet" href="../../assets/css/documentotipo/documentotipo_globalstyles.css" />
</head>
<body>

  <header>
    <h1>Agregar Nuevo Tipo de Documento</h1>
    <nav class="breadcrumb">
      <a href="../dashboard.php">Inicio</a> <span>›</span>
      <a href="../documentos/documento_list.php">Documentos</a> <span>›</span>
      <a href="documentotipo_list.php">Tipos de Documentos</a> <span>›</span>
      <span>Agregar</span>
    </nav>
  </header>

  <main>
    <div class="card form-container">
      <h2>Registrar Nuevo Tipo de Documento</h2>
      <p>Completa el siguiente formulario para registrar un nuevo tipo de documento en el sistema.</p>

      <form action="documentotipo_add_action.php" method="POST" class="form">

        <!-- Nombre -->
        <div class="field">
          <label for="nombre">Nombre del documento <span style="color:red">*</span></label>
          <input 
            type="text" 
            id="nombre" 
            name="nombre" 
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
            placeholder="Describe brevemente el propósito del documento..."></textarea>
        </div>

        <!-- Obligatorio -->
        <div class="field">
          <label for="obligatorio">¿Es obligatorio?</label>
          <select name="obligatorio" id="obligatorio" required>
            <option value="1">Sí, es obligatorio</option>
            <option value="0">No, es opcional</option>
          </select>
        </div>

        <!-- Botones -->
        <div class="form-actions">
          <button type="submit" class="btn btn-success">Guardar Tipo</button>
          <a href="documentotipo_list.php" class="btn btn-secondary">Cancelar</a>
        </div>

      </form>
    </div>
  </main>

</body>
</html>
