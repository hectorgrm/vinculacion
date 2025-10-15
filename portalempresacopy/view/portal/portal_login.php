<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Acceso al Portal de Empresas - Residencia Profesional</title>
  <link rel="stylesheet" href="../../assets/css/portal/portal_login.css" />
</head>
<body>

  <div class="login-container">
    <img src="../../assets/img/tec_logo.png" alt="Logo Tecnológico">
    <h1>Acceso Empresarial</h1>
    <p>Introduce tu <strong>Token</strong> y <strong>NIP</strong> para ingresar al portal seguro.</p>

    <!-- ✅ Mensaje de error o éxito (opcional, dinámico desde backend) -->
    <!-- <div class="alert alert-danger">Token o NIP incorrecto.</div> -->
    <!-- <div class="alert alert-success">Acceso exitoso, redirigiendo...</div> -->

    <form action="portal_auth.php" method="POST">
      <div class="form-group">
        <label for="token">Token de Acceso</label>
        <input type="text" id="token" name="token" placeholder="Ej. 123e4567-e89b-12d3-a456-426614174000" required />
      </div>

      <div class="form-group">
        <label for="nip">NIP</label>
        <input type="password" id="nip" name="nip" placeholder="••••••••" maxlength="10" required />
      </div>

      <button type="submit" class="btn-login">Ingresar</button>
    </form>

    <a href="index.php" class="back-link">← Volver a inicio</a>
  </div>

</body>
</html>
