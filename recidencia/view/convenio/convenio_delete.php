<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Eliminar Convenio - Residencia Profesional</title>
  <link rel="stylesheet" href="../../assets/css/convenios/convenioglobalstyles.css" />
</head>
<body>

<header>
  <h1>Eliminar Convenio</h1>
  <nav class="breadcrumb">
    <a href="../dashboard.php">Inicio</a> <span>/</span>
    <a href="convenio_list.php">Convenios</a> <span>/</span>
    <span>Eliminar</span>
  </nav>
</header>

<main>

  <section class="card danger-zone">
    <h2>⚠️ Zona de ELIMINAR</h2>
    <p>
      Estás a punto de <strong>eliminar un convenio</strong> de forma permanente.  
      Esta acción no se puede deshacer y podría afectar otros módulos vinculados
      (documentos, empresas, comentarios de machote, etc.).
    </p>

    <div class="info-box">
      <h3>📄 Detalles del convenio a eliminar:</h3>
      <ul>
        <li><strong>ID:</strong> 2</li>
        <li><strong>Empresa:</strong> Tequila ECT</li>
        <li><strong>Estatus:</strong> En revisión</li>
        <li><strong>Fecha inicio:</strong> 2025-08-15</li>
        <li><strong>Fecha fin:</strong> No definida</li>
        <li><strong>Versión:</strong> v1.0</li>
      </ul>
      <!-- 🔁 Todos estos datos se llenarán dinámicamente desde la BD -->
    </div>

    <form class="form" method="POST" action="convenio_delete_action.php">
      <!-- ID oculto del convenio -->
      <input type="hidden" name="id" value="2" />

      <div class="field">
        <label for="confirmacion">Escribe <strong>ELIMINAR</strong> para confirmar:</label>
        <input type="text" name="confirmacion" id="confirmacion" placeholder="ELIMINAR" required />
      </div>

      <div class="actions">
        <button type="submit" class="btn btn-danger">🗑️ Eliminar Convenio</button>
        <a href="convenio_list.php" class="btn btn-secondary">Cancelar</a>
      </div>
    </form>

  </section>

</main>

</body>
</html>
