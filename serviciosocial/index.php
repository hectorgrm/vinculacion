<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/session.php';

$user = $_SESSION['user'] ?? null;
$allowedRoles = ['ss_admin', 'admin_ss'];

if (!is_array($user) || !in_array(strtolower((string)($user['role'] ?? '')), $allowedRoles, true)) {
    header('Location: ../common/login.php?module=serviciosocial&error=unauthorized');
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Servicio Social</title>
    <link rel="stylesheet" href="assets/serviciosocialstyles.css">
</head>
<body>
    <header>
        <h1>Dashboard Servicio Social</h1>
        <p class="welcome">Hola, <?php echo htmlspecialchars((string)($user['name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
    </header>
    <nav>
        <a href="#estudiantes">Estudiantes</a>
        <a href="#plazas">Plazas</a>
        <a href="#servicios">Servicios</a>
        <a href="#periodos">Periodos</a>
        <a href="#documentos">Documentos</a>
        <a href="#reportes">Reportes</a>
        <a href="../common/logout.php">Cerrar sesión</a>
    </nav>
    <main>
        <h2>Bienvenido al módulo de Servicio Social</h2>
        <p>Selecciona una de las opciones para comenzar:</p>
        <div class="card-container">
            <div class="card" id="estudiantes">
                <h3>Gestión de Estudiantes</h3>
                <a href="view/gestionestudiante/estudiante_list.php">Entrar</a>
            </div>
            <div class="card" id="plazas">
                <h3>Gestión de Plazas</h3>
                <a href="view/gestionplaza/plaza_list.php">Entrar</a>
            </div>
            <div class="card" id="servicios">
                <h3>Expediente de Servicio</h3>
                <a href="servicio_list.php">Entrar</a>
            </div>
            <div class="card" id="periodos">
                <h3>Periodos</h3>
                <a href="periodo_list.php">Entrar</a>
            </div>
            <div class="card" id="documentos">
                <h3>Documentos</h3>
                <a href="docss_list.php">Entrar</a>
            </div>
            <div class="card" id="reportes">
                <h3>Reportes</h3>
                <a href="reportes.php">Entrar</a>
            </div>
        </div>
    </main>
</body>
</html>
