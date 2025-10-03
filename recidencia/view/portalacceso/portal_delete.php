<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eliminar Acceso - Portal de Empresa</title>
  <link rel="stylesheet" href="../../assets/css/portal/portalglobalstyles.css">
</head>
<body>

  <header>
    <h1>Eliminar Acceso al Portal</h1>
    <nav class="breadcrumb">
      <a href="../dashboard.php">Inicio</a> <span>›</span>
      <a href="../empresas/empresa_list.php">Empresas</a> <span>›</span>
      <a href="../convenios/convenio_list.php">Convenios</a> <span>›</span>
      <a href="portal_list.php">Portal de Acceso</a> <span>›</span>
      <span>Eliminar</span>
    </nav>
  </header>

  <main>
    <div class="card danger-zone">
      <h2>⚠️ Confirmar eliminación</h2>
      <p>
        Estás a punto de eliminar el acceso al portal para la siguiente empresa.  
        Esta acción <strong>no se puede deshacer</strong> y eliminará el token de acceso y el NIP asociado.
      </p>

      <div class="alert alert-danger">
        <strong>Importante:</strong> Si la empresa aún tiene acceso activo o el portal está en uso, asegúrate de revocar el acceso antes de continuar.
      </div>

      <div class="delete-details">
        <ul>
          <li><strong>ID:</strong> 1</li>
          <li><strong>Empresa:</strong> Casa del Barrio</li>
          <li><strong>Convenio asociado:</strong> #1 (v1.2)</li>
          <li><strong>Token:</strong> 11111111-1111-1111-1111-111111111111</li>
          <li><strong>NIP:</strong> 123456</li>
          <li><strong>Fecha de expiración:</strong> 2025-12-31</li>
          <li><strong>Estado:</strong> Activo ✅</li>
        </ul>
      </div>

      <form action="portal_delete_action.php" method="POST">
        <input type="hidden" name="id" value="1"> <!-- 🧪 ID dinámico -->
        <div class="form-actions">
          <button type="submit" class="btn btn-danger">Eliminar Definitivamente</button>
          <a href="portal_list.php" class="btn btn-secondary">Cancelar</a>
        </div>
      </form>
    </div>
  </main>

</body>
</html>
