<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/session.php';

$user = $_SESSION['user'] ?? null;
$allowedRoles = ['res_admin'];

if (!is_array($user) || !in_array(strtolower((string)($user['role'] ?? '')), $allowedRoles, true)) {
    header('Location: ../common/login.php?module=recidencia&error=unauthorized');
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Residencia / Vinculación</title>
    <link rel="stylesheet" href="assets/stylesrecidencia.css">
</head>
<body>
    <header>
        <h1>Dashboard Residencia / Vinculación</h1>
        <p class="welcome">Hola, <?php echo htmlspecialchars((string)($user['name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
    </header>
    <nav>
        <a href="#empresas">Empresas</a>
        <a href="#convenios">Convenios</a>
        <a href="#documentos">Documentos</a>
        <a href="#reportes">Reportes</a>
        <a href="../common/logout.php">Cerrar sesión</a>
    </nav>
    <main>
        <h2>Bienvenido al módulo de Residencia</h2>
        <p>Selecciona una de las opciones para comenzar:</p>
        <div class="card-container">
            <div class="card" id="empresas">
                <h3>Gestión de Empresas</h3>
                <a href="empresa_list.php">Entrar</a>
            </div>
            <div class="card" id="convenios">
                <h3>Gestión de Convenios</h3>
                <a href="convenio_list.php">Entrar</a>
            </div>
            <div class="card" id="documentos">
                <h3>Gestión de Documentos</h3>
                <a href="documento_list.php">Entrar</a>
            </div>
            <div class="card" id="reportes">
                <h3>Reportes</h3>
                <a href="reportes.php">Entrar</a>
            </div>
        </div>
    </main>
</body>
</html>
