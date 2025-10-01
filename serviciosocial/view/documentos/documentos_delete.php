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
      <a href="documentos_list.php">Gestión de Documentos</a>
      <span>›</span>
      <span>Eliminar</span>
    </nav>
  </header>

  <main>
    <a href="documentos_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

    <section class="card form-card--danger">
      <h2>⚠️ Confirmar eliminación</h2>
      <p>
        Estás a punto de <strong>eliminar permanentemente</strong> el siguiente documento del sistema.  
        Esta acción <strong>no se puede deshacer</strong>. Por favor, revisa la información antes de confirmar.
      </p>

      <!-- 📄 Resumen del documento -->
      <div class="grid cols-2 resumen-servicio">
        <div class="field">
          <label>ID del Documento</label>
          <p>15</p>
        </div>
        <div class="field">
          <label>Empresa</label>
          <p>Tech Solutions S.A. de C.V.</p>
        </div>
        <div class="field">
          <label>Convenio</label>
          <p>Convenio 2025-01</p>
        </div>
        <div class="field">
          <label>Tipo de Documento</label>
          <p>Carta de Intención</p>
        </div>
        <div class="field">
          <label>Fecha de creación</label>
          <p>05/02/2025</p>
        </div>
        <div class="field">
          <label>Estado</label>
          <p><span class="status pendiente">Pendiente</span></p>
        </div>
      </div>

      <!-- ⚠️ Confirmación -->
      <form action="" method="post" class="form">
        <div class="field">
          <label for="confirmacion" class="required">Escribe <strong>ELIMINAR</strong> para confirmar</label>
          <input type="text" id="confirmacion" name="confirmacion" placeholder="ELIMINAR" required />
          <div class="hint">Esta verificación evita eliminaciones accidentales.</div>
        </div>

        <!-- ✅ Acciones -->
        <div class="actions">
          <a href="documentos_list.php" class="btn btn-secondary">Cancelar</a>
          <button type="submit" class="btn btn-danger">🗑️ Eliminar Documento</button>
        </div>
      </form>
    </section>
  </main>

</body>
</html>
