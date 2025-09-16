<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/styleslogin.css">
</head>
<body>
    <div class="login-card">
        <h1>Iniciar Sesion</h1>
        <form method="POST" action="AuthController.php">
            <div class="form-group">
                <label for="email">Correo electronico</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contrasena</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Ingresar</button>
        </form>
        <a href="../index.php" class="extra-link">Volver al inicio</a>
    </div>
</body>
</html>