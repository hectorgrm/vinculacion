<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detalle del Estudiante · Residencia Profesional</title>

  <link rel="stylesheet" href="../../assets/css/global.css" />
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <link rel="stylesheet" href="../../assets/css/modules/estudiante.css" />

  <style>
    /* Estilos mínimos si aún no tienes el CSS cargado */
    body {
      font-family: "Inter", sans-serif;
      background: #f6f8fa;
      margin: 0;
      color: #2c3e50;
    }
    .main {
      max-width: 1100px;
      margin: 2rem auto;
      padding: 0 1.5rem;
    }
    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }
    .btn {
      background: #0055aa;
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      text-decoration: none;
      font-size: 0.9rem;
    }
    .btn.secondary {
      background: #7f8c8d;
    }
    .card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
      margin-bottom: 1.8rem;
      overflow: hidden;
    }
    .card header {
      background: #f1f3f5;
      padding: 0.8rem 1.2rem;
      font-weight: 600;
      border-bottom: 1px solid #e5e7ea;
    }
    .content {
      padding: 1.2rem;
    }
    .grid-2 {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 1.5rem;
    }
    .data-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .data-list li {
      padding: 0.4rem 0;
      border-bottom: 1px solid #ececec;
    }
    .badge {
      padding: 4px 8px;
      border-radius: 6px;
      color: #fff;
      font-size: 0.85rem;
    }
    .badge.activo { background: #27ae60; }
    .badge.finalizado { background: #2980b9; }
    .badge.inactivo { background: #c0392b; }
    .muted { color: #7f8c8d; font-size: 0.9rem; }
    .foot { text-align: center; color: #888; margin-top: 2rem; font-size: 0.9rem; }
  </style>
</head>

<body>
 <div class="app">  
         <?php include __DIR__ . '/../../layout/sidebar.php'; ?>
  <main class="main">

    <!-- Encabezado -->
    <header class="topbar">
      <div>
        <h2>👨‍🎓 Detalle del Estudiante</h2>
        <p class="subtitle">Consulta la información registrada de un estudiante y su vinculación con empresa y convenio.</p>
      </div>
      <div class="actions">
        <a href="estudiante_list.php" class="btn secondary">← Regresar</a>
        <a href="estudiante_edit.php?id=1" class="btn">✏️ Editar</a>
      </div>
    </header>

    <!-- Card principal -->
    <section class="card profile-card">
      <header>Información del estudiante</header>
      <div class="content grid-2">
        
        <!-- Datos personales -->
        <div class="profile-section">
          <h4>👤 Datos personales</h4>
          <ul class="data-list">
            <li><strong>Nombre completo:</strong> Juan Carlos Pérez López</li>
            <li><strong>Matrícula:</strong> 20230145</li>
            <li><strong>Carrera:</strong> Ingeniería en Informática</li>
            <li><strong>Correo institucional:</strong> juan.perez@universidad.mx</li>
            <li><strong>Teléfono:</strong> 3312345678</li>
          </ul>
        </div>

        <!-- Empresa y convenio -->
        <div class="profile-section">
          <h4>🏢 Empresa asignada</h4>
          <ul class="data-list">
            <li><strong>Empresa:</strong> Barbería Gómez</li>
            <li><strong>Contacto:</strong> barberiagomez@gmail.com</li>
          </ul>

          <h4 style="margin-top:1rem;">📑 Convenio relacionado</h4>
          <ul class="data-list">
            <li><strong>Folio:</strong> CVN-2025-001</li>
            <li><strong>Estatus:</strong> <span class="badge activo">Activo</span></li>
          </ul>
        </div>

      </div>
    </section>

    <!-- Card inferior -->
    <section class="card">
      <header>📅 Información adicional</header>
      <div class="content">
        <ul class="data-list">
          <li><strong>Estatus del estudiante:</strong> <span class="badge activo">Activo</span></li>
          <li><strong>Fecha de registro:</strong> 2025-10-15 14:32:00</li>
        </ul>
      </div>
    </section>

    <p class="foot">Área de Vinculación · Residencias Profesionales</p>
  </main>
</div>
</body>
</html>
