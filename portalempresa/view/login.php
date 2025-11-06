<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/session.php';

$errorKey = isset($_GET['error']) ? (string) $_GET['error'] : '';
$statusKey = isset($_GET['status']) ? (string) $_GET['status'] : '';

$errorMessage = '';
$statusMessage = '';

switch ($errorKey) {
    case 'invalid':
        $errorMessage = 'Token o NIP incorrecto.';
        break;
    case 'expired':
        $errorMessage = 'Acceso expirado o bloqueado. Solicita uno nuevo con Vinculación.';
        break;
    case 'missing':
        $errorMessage = 'Debes ingresar el token y el NIP para continuar.';
        break;
    case 'session':
        $errorMessage = 'Tu sesión finalizó. Ingresa nuevamente.';
        break;
    case 'inactive':
        $errorMessage = 'Tu acceso fue deshabilitado por el administrador.';
        break;
    case 'server':
        $errorMessage = 'Ocurrió un problema al validar tus datos. Intenta otra vez en unos minutos.';
        break;
}

if ($statusKey === 'loggedout') {
    $statusMessage = 'Sesión cerrada correctamente.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Acceso al Portal de Empresa · Residencias Profesionales</title>

  <style>
    * {
      box-sizing: border-box;
    }
    body {
      background: #f8fafc;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .login-card {
      background: white;
      border-radius: 16px;
      padding: 36px 32px;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
      animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .login-card h2 {
      text-align: center;
      color: #0f172a;
      margin-bottom: 6px;
      font-size: 1.6rem;
    }

    .login-card p {
      text-align: center;
      color: #64748b;
      margin-bottom: 28px;
      font-size: 15px;
    }

    .field {
      margin-bottom: 18px;
    }

    .field label {
      display: block;
      font-weight: 600;
      color: #334155;
      margin-bottom: 6px;
    }

    .field input {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      font-size: 15px;
      transition: border-color 0.2s, box-shadow 0.2s;
      outline: none;
    }

    .field input:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 2px rgba(37,99,235,0.2);
    }

    .btn {
      display: block;
      background: #2563eb;
      color: white;
      font-weight: 600;
      border: none;
      padding: 10px 14px;
      border-radius: 8px;
      cursor: pointer;
      width: 100%;
      font-size: 16px;
      transition: background 0.2s ease;
    }

    .btn:hover {
      background: #1d4ed8;
    }

    .footer {
      text-align: center;
      margin-top: 18px;
      font-size: 13px;
      color: #94a3b8;
    }

    .logo {
      text-align: center;
      margin-bottom: 16px;
    }
    .logo img {
      width: 80px;
      height: auto;
      border-radius: 50%;
      border: 2px solid #e2e8f0;
      box-shadow: 0 0 6px rgba(0,0,0,0.05);
    }

    /* Alertas simuladas */
    .alert {
      padding: 10px 12px;
      border-radius: 8px;
      margin-bottom: 14px;
      font-size: 14px;
      text-align: center;
    }
    .alert.error {
      background: #fee2e2;
      color: #991b1b;
    }
    .alert.success {
      background: #dcfce7;
      color: #166534;
    }
  </style>
</head>
<body>

  <form class="login-card" action="../handler/login_handler.php" method="post">
    <div class="logo">
      <img src="../assets/img/logo_vinculacion.png" alt="Logo Vinculación">
    </div>

    <h2>🔐 Acceso al Portal de Empresa</h2>
    <p>Ingresa el Token y NIP proporcionados por Vinculación.</p>

    <?php if ($errorMessage !== ''): ?>
      <div class="alert error"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <?php if ($statusMessage !== ''): ?>
      <div class="alert success"><?php echo htmlspecialchars($statusMessage, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <div class="field">
      <label for="token">Token de acceso</label>
      <input id="token" name="token" type="text" placeholder="Ej: 0f1c42d3-97f2-4fda-bc11-0f84b70a45de" required>
    </div>

    <div class="field">
      <label for="nip">NIP</label>
      <input id="nip" name="nip" type="text" maxlength="6" placeholder="Ej: 4567" required>
    </div>

    <button class="btn" type="submit">Ingresar</button>

    <p class="footer">© 2025 Vinculación · Residencias Profesionales</p>
  </form>

</body>
</html>
