<?php
declare(strict_types=1);

// 🔐 VALIDACIÓN DE ACCESO (simulada)
$empresaId = $_GET['empresa'] ?? 45; 
$token = $_GET['token'] ?? 'ABC123';

// (Aquí luego se valida con rp_portal_acceso si el token es válido)
// Ejemplo temporal de datos cargados desde la BD:
$empresa = [
  'nombre' => 'Casa del Barrio',
  'representante' => 'José Velador',
  'email' => 'contacto@casadelbarrio.mx',
  'estatus' => 'Activa'
];
$convenio = [
  'id' => 12,
  'estado' => 'En revisión',
  'fecha_inicio' => '2025-09-01',
  'fecha_fin' => '2026-09-01',
  'version' => 'v1.2'
];
$documentos = [
  'total' => 5,
  'aprobados' => 3,
  'pendientes' => 2
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal Empresa · <?php echo htmlspecialchars($empresa['nombre']); ?></title>
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css"/>
  <link rel="stylesheet" href="./assets/css/portalempresa.css"/>
</head>
<body>
  <main class="portal">
    <header class="portal-header">
      <div class="brand">
        <h1>🏢 <?php echo htmlspecialchars($empresa['nombre']); ?></h1>
        <p>Portal de Residencias Profesionales</p>
      </div>
      <div class="actions">
        <a href="perfil.php?empresa=<?php echo $empresaId; ?>" class="btn">👤 Perfil</a>
        <a href="logout.php" class="btn danger">🚪 Cerrar sesión</a>
      </div>
    </header>

    <section class="summary-grid">
      <div class="card">
        <h3>📑 Convenio</h3>
        <p><strong>Estado:</strong> <?php echo $convenio['estado']; ?></p>
        <p><small>Versión: <?php echo $convenio['version']; ?></small></p>
        <a href="machote_view.php?id=<?php echo $convenio['id']; ?>" class="btn">Ver detalles</a>
      </div>

      <div class="card">
        <h3>📂 Documentos</h3>
        <p><?php echo $documentos['aprobados']; ?> de <?php echo $documentos['total']; ?> aprobados</p>
        <div class="progress">
          <div class="bar" style="width: <?php echo ($documentos['aprobados']/$documentos['total'])*100; ?>%"></div>
        </div>
        <a href="documentos.php?empresa=<?php echo $empresaId; ?>" class="btn">Ver / Subir</a>
      </div>

      <div class="card">
        <h3>📥 Machote</h3>
        <p>Convenio base revisado y disponible.</p>
        <a href="convenio.php#machote" class="btn">Ver documento</a>
      </div>

      <div class="card">
        <h3>💬 Comentarios</h3>
        <p>1 hilo abierto en revisión</p>
        <a href="convenio.php#comentarios" class="btn">Revisar</a>
      </div>
    </section>

    <section class="info">
      <h2>📊 Resumen general</h2>
      <ul>
        <li><strong>Representante:</strong> <?php echo htmlspecialchars($empresa['representante']); ?></li>
        <li><strong>Correo:</strong> <?php echo htmlspecialchars($empresa['email']); ?></li>
        <li><strong>Estatus:</strong> <?php echo htmlspecialchars($empresa['estatus']); ?></li>
        <li><strong>Vigencia del convenio:</strong> <?php echo $convenio['fecha_inicio']; ?> → <?php echo $convenio['fecha_fin']; ?></li>
      </ul>
    </section>

    <footer class="portal-footer">
      <p>Universidad Tecnológica · Departamento de Vinculación y Residencias</p>
      <p><a href="soporte.php">¿Necesitas ayuda?</a></p>
    </footer>
  </main>
</body>
</html>
