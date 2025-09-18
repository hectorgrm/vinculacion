<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';

$user = $_SESSION['user'] ?? null;
$allowedRoles = ['estudiante'];

if (!is_array($user) || !in_array(strtolower((string)($user['role'] ?? '')), $allowedRoles, true)) {
    header('Location: ../common/login.php?module=estudiante&error=unauthorized');
    exit;

}

$userName = (string)($user['name'] ?? '');

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Portal Estudiante</title>
 <link rel="stylesheet" href="assets/estudiantestyles.css">
</head>
<body>
  <header>
    <h1>Portal del Estudiante</h1>
  </header>
  <nav>
    <a href="#expediente">Mi Expediente</a>
    <a href="#descargas">Descargas</a>
    <a href="../common/logout.php">Cerrar sesión</a>
  </nav>
  <main>
    <h2>Bienvenido, <?php echo 
    htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?></h2>
  <p>Aquí puedes consultar tu información y descargar los formatos necesarios.</p>
    <div class="card-container">
      <div class="card" id="expediente">
        <h3>Mi Expediente</h3>
        <a href="expediente.php">Entrar</a>
      </div>
      <div class="card" id="descargas">
        <h3>Descargar Documentos</h3>
        <a href="descargas.php">Entrar</a>
      </div>
    </div>
  </main>
</body>
</html>
