<?php

declare(strict_types=1);

$module = strtolower(trim((string)($_GET['module'] ?? '')));

$moduleTitles = [
    'recidencia' => 'Residencia',
    'serviciosocial' => 'Servicio Social',
    'serviciosocial_admin' => 'Administracion Servicio Social',
    'estudiante' => 'Estudiante',

];

$title = $moduleTitles[$module] ?? 'Plataforma de Vinculación';
$errorKey = (string)($_GET['error'] ?? '');

$errorMessages = [
    'missing_fields' => 'Por favor ingresa tu correo electrónico y contraseña.',
    'invalid_credentials' => 'Credenciales incorrectas. Intenta nuevamente.',
    'unauthorized' => 'Tu usuario no tiene permisos para acceder a este módulo.',
    'unexpected' => 'Ocurrió un error al validar tus datos. Intenta más tarde.',
];

$errorMessage = $errorMessages[$errorKey] ?? '';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars('Iniciar sesión - ' . $title, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="assets/styleslogin.css">
</head>
<body>
    <div class="login-card">
        <h1>Iniciar sesión</h1>
        <p class="module-hint">Módulo seleccionado: <strong><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></strong></p>
        <?php if ($errorMessage !== ''): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="controller/AuthController.php">
            <input type="hidden" name="module" value="<?php echo htmlspecialchars($module, ENT_QUOTES, 'UTF-8'); ?>">
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" required autocomplete="username">
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            <button type="submit">Ingresar</button>
        </form>
        <a href="../index.php" class="extra-link">Volver al inicio</a>
    </div>
</body>
</html>
