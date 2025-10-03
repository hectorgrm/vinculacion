<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Eliminar Empresa · Residencia Profesional</title>
  <link rel="stylesheet" href="../../assets/css/empresas/empresaglobalstyles.css" />

</head>
<body>

  <!-- HEADER -->
  <header>
    <h1>Eliminar Empresa</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="empresa_list.php">Empresas</a>
      <span>›</span>
      <span>Eliminar</span>
    </nav>
  </header>

  <!-- MAIN -->
  <main>
    <div class="danger-zone">
      <h2>⚠️ Eliminar Empresa</h2>
      <p>
        Estás a punto de <strong>eliminar definitivamente</strong> la empresa <strong>"Casa del Barrio"</strong> (ID: #1).  
        Esta acción no se puede deshacer.  
        <br><br>
        <strong>Verifica antes:</strong> Si la empresa tiene convenios activos, primero debes eliminarlos antes de poder continuar.
      </p>

      <form action="" method="post">
        <div class="danger-actions">
          <a href="empresa_list.php" class="btn-secondary">⬅ Cancelar</a>
          <button type="submit" class="btn-danger">🗑️ Eliminar Empresa</button>
        </div>
      </form>
    </div>
  </main>

</body>
</html>
