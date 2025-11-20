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
  /* ============================================
   GLOBAL RESET + VARIABLES
   ============================================ */

:root {
  --primary: #204bff;
  --primary-dark: #1534b8;
  --bg-light: #f1f5f9;
  --text-main: #0f172a;
  --text-muted: #64748b;
  --radius: 14px;
  --shadow-card: 0 18px 45px rgba(15, 23, 42, 0.14);
  --shadow-soft: 0 8px 25px rgba(15, 23, 42, 0.08);
  --transition: 0.25s ease;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* ============================================
   PAGE BACKGROUND
   ============================================ */

body {
  font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI";
  background: radial-gradient(circle at top left, rgba(32, 75, 255, 0.15), transparent 60%),
              radial-gradient(circle at bottom right, rgba(0, 201, 157, 0.15), transparent 60%),
              #eef2f7;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

/* ============================================
   LOGIN CARD
   ============================================ */

.login-card {
  width: 100%;
  max-width: 420px;
  background: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(18px);
  border-radius: 20px;
  padding: 36px 32px;
  box-shadow: var(--shadow-card);
  animation: slideIn 0.6s ease;
}

@keyframes slideIn {
  from { opacity: 0; transform: translateY(25px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ============================================
   LOGO
   ============================================ */

.logo {
  text-align: center;
  margin-bottom: 20px;
}

.logo img {
  width: 160px;
  height: auto;
  border-radius: 12px;
  box-shadow: var(--shadow-soft);
}

/* ============================================
   TEXT HEADERS
   ============================================ */

.login-card h2 {
  text-align: center;
  color: var(--text-main);
  font-weight: 700;
  font-size: 1.5rem;
  margin-bottom: 6px;
}

.login-card p {
  text-align: center;
  color: var(--text-muted);
  margin-bottom: 26px;
  font-size: 14px;
}

/* ============================================
   FORM FIELDS
   ============================================ */

.field {
  margin-bottom: 18px;
}

.field label {
  display: block;
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 6px;
  font-size: 0.92rem;
}

.field input {
  width: 100%;
  padding: 12px 14px;
  border-radius: var(--radius);
  border: 1px solid #cbd5e1;
  background: #f8fafc;
  font-size: 0.95rem;
  transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
}

.field input:focus {
  border-color: var(--primary);
  background: white;
  box-shadow: 0 0 0 3px rgba(32, 75, 255, 0.25);
  outline: none;
}

/* ============================================
   BUTTON
   ============================================ */

.btn {
  width: 100%;
  padding: 12px 14px;
  margin-top: 4px;
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  color: white;
  font-weight: 600;
  font-size: 1rem;
  border: none;
  border-radius: var(--radius);
  cursor: pointer;
  transition: background var(--transition), transform var(--transition), box-shadow var(--transition);
}

.btn:hover {
  background: linear-gradient(135deg, var(--primary-dark), var(--primary));
  box-shadow: 0 6px 18px rgba(32, 75, 255, 0.45);
  transform: translateY(-1px);
}

/* ============================================
   FOOTER / COPYRIGHT
   ============================================ */

.footer {
  text-align: center;
  color: #94a3b8;
  font-size: 12px;
  margin-top: 20px;
}

/* ============================================
   ALERTS (ERROR/SUCCESS)
   ============================================ */

.alert {
  padding: 12px 14px;
  border-radius: var(--radius);
  font-size: 14px;
  margin-bottom: 14px;
  text-align: center;
  font-weight: 500;
}

.alert.error {
  background: #fee2e2;
  color: #991b1b;
  border: 1px solid #fecaca;
}

.alert.success {
  background: #dcfce7;
  color: #14532d;
  border: 1px solid #bbf7d0;
}

</style>
</head>
<body>

  <form class="login-card" action="../handler/login_handler.php" method="post">
    <div class="logo">
      <img src="../assets/img/logo_vinculacion.jpg" alt="Logo Vinculación">
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
