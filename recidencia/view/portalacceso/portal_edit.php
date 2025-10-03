<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Acceso - Portal de Empresa</title>
  <link rel="stylesheet" href="../../assets/css/portal/portalglobalstyles.css">
</head>
<body>

  <header>
    <h1>Editar Acceso al Portal</h1>
    <nav class="breadcrumb">
      <a href="../dashboard.php">Inicio</a> <span>›</span>
      <a href="../empresas/empresa_list.php">Empresas</a> <span>›</span>
      <a href="../convenios/convenio_list.php">Convenios</a> <span>›</span>
      <a href="portal_list.php">Portal de Acceso</a> <span>›</span>
      <span>Editar</span>
    </nav>
  </header>

  <main>
    <div class="card form-container">
      <h2>Actualizar Datos del Portal</h2>
      <p>Modifica la información del acceso al portal de la empresa.</p>

      <form action="portal_edit_action.php" method="POST" class="form">
        <!-- Campo oculto con el ID -->
        <input type="hidden" name="id" value="1"> <!-- 🧪 Este valor será dinámico -->

        <!-- Empresa -->
        <div class="field">
          <label for="empresa_id">Empresa:</label>
          <select name="empresa_id" id="empresa_id" required>
            <option value="">-- Selecciona una empresa --</option>
            <option value="1" selected>Casa del Barrio</option>
            <option value="2">Tequila ECT</option>
            <option value="3">Industrias Yakumo</option>
          </select>
        </div>

        <!-- Convenio -->
        <div class="field">
          <label for="convenio_id">Convenio asociado (opcional):</label>
          <select name="convenio_id" id="convenio_id">
            <option value="">-- Sin convenio asociado --</option>
            <option value="1" selected>Convenio #1 - v1.2</option>
            <option value="2">Convenio #2 - v1.0</option>
            <option value="3">Convenio #3 - pendiente</option>
          </select>
        </div>

        <!-- Token -->
        <div class="field">
          <label for="token">Token de acceso (UUID):</label>
          <input type="text" name="token" id="token" value="11111111-1111-1111-1111-111111111111" required>
          <small>Este token puede ser regenerado o modificado si es necesario.</small>
        </div>

        <!-- NIP -->
        <div class="field">
          <label for="nip">NIP de seguridad:</label>
          <input type="text" name="nip" id="nip" maxlength="10" value="123456" required>
          <small>El NIP se utiliza como código de seguridad adicional.</small>
        </div>

        <!-- Expiración -->
        <div class="field">
          <label for="expiracion">Fecha de expiración:</label>
          <input type="date" name="expiracion" id="expiracion" value="2025-12-31" required>
        </div>

        <!-- Estado -->
        <div class="field">
          <label for="activo">Estado:</label>
          <select name="activo" id="activo" required>
            <option value="1" selected>Activo</option>
            <option value="0">Inactivo</option>
          </select>
        </div>

        <!-- Botones -->
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
          <a href="portal_list.php" class="btn btn-secondary">Cancelar</a>
        </div>

      </form>
    </div>
  </main>

</body>
</html>
