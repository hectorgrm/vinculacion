<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Iniciar Sesión - Portal del Estudiante</title>
  <link rel="stylesheet" href="../assets/css/estudiante_login.css" />
</head>
<body>

  <div class="login-container">
    <div class="login-card">
      <h1>🎓 Portal del Estudiante</h1>
      <p class="subtitle">Accede con tu matrícula y contraseña para continuar</p>

      <!-- Mensaje de error (opcional, se mostrará si el login falla) -->
      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
          ⚠️ Matrícula o contraseña incorrectos. Inténtalo de nuevo.
        </div>
      <?php endif; ?>

      <form action="estudiante_login_action.php" method="POST" class="login-form">
        <div class="form-group">
          <label for="matricula">Matrícula</label>
          <input type="text" name="matricula" id="matricula" placeholder="Ej. A012345" required />
        </div>

        <div class="form-group">
          <label for="password">Contraseña</label>
          <input type="password" name="password" id="password" placeholder="••••••••" required />
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
          <a href="index.php" class="btn btn-secondary">Volver</a>
        </div>
      </form>

      <div class="help-section">
        <p>¿Olvidaste tu contraseña? <a href="#">Recupérala aquí</a></p>
        <p>Si tienes problemas para acceder, comunícate con el departamento de <strong>Servicio Social</strong>.</p>
      </div>
    </div>
  </div>

</body>
</html>
