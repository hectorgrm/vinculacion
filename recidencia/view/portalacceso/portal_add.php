<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrar Acceso - Portal de Empresa</title>
  <link rel="stylesheet" href="../../assets/css/portal/portalglobalstyles.css">
</head>
<body>

  <header>
    <h1>Registrar Nuevo Acceso al Portal</h1>
    <nav class="breadcrumb">
      <a href="../dashboard.php">Inicio</a> <span>›</span>
      <a href="../empresas/empresa_list.php">Empresas</a> <span>›</span>
      <a href="../convenios/convenio_list.php">Convenios</a> <span>›</span>
      <a href="portal_list.php">Portal de Acceso</a> <span>›</span>
      <span>Agregar</span>
    </nav>
  </header>

  <main>
    <div class="card form-container">
      <h2>Crear Nuevo Portal de Acceso</h2>
      <p>Llena el siguiente formulario para generar el acceso que será compartido con la empresa.</p>

      <form action="portal_add_action.php" method="POST" class="form">

        <!-- Empresa -->
        <div class="field">
          <label for="empresa_id">Empresa:</label>
          <select name="empresa_id" id="empresa_id" required>
            <option value="">-- Selecciona una empresa --</option>
            <option value="1">Casa del Barrio</option>
            <option value="2">Tequila ECT</option>
            <option value="3">Industrias Yakumo</option>
          </select>
        </div>

        <!-- Convenio (opcional) -->
        <div class="field">
          <label for="convenio_id">Convenio asociado (opcional):</label>
          <select name="convenio_id" id="convenio_id">
            <option value="">-- Sin convenio asociado --</option>
            <option value="1">Convenio #1 - v1.2</option>
            <option value="2">Convenio #2 - v1.0</option>
            <option value="3">Convenio #3 - pendiente</option>
          </select>
        </div>

        <!-- Token -->
        <div class="field">
          <label for="token">Token de acceso (UUID):</label>
          <input type="text" name="token" id="token" placeholder="Ej: 123e4567-e89b-12d3-a456-426614174000" required>
          <small>Este token se genera automáticamente o se puede ingresar manualmente.</small>
        </div>

        <!-- NIP -->
        <div class="field">
          <label for="nip">NIP de seguridad:</label>
          <input type="text" name="nip" id="nip" maxlength="10" placeholder="Ej: 123456" required>
          <small>El NIP es un código corto que la empresa utilizará junto con el token.</small>
        </div>

        <!-- Expiración -->
        <div class="field">
          <label for="expiracion">Fecha de expiración:</label>
          <input type="date" name="expiracion" id="expiracion" required>
        </div>

        <!-- Estado -->
        <div class="field">
          <label for="activo">Estado:</label>
          <select name="activo" id="activo" required>
            <option value="1">Activo</option>
            <option value="0">Inactivo</option>
          </select>
        </div>

        <!-- Botones -->
        <div class="form-actions">
          <button type="submit" class="btn btn-success">Crear Acceso</button>
          <a href="portal_list.php" class="btn btn-secondary">Cancelar</a>
        </div>

      </form>
    </div>
  </main>

</body>
</html>
