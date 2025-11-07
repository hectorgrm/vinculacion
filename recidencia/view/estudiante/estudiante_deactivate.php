<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Desactivar Estudiante · Residencia Profesional</title>

  <link rel="stylesheet" href="../../assets/css/global.css" />
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <link rel="stylesheet" href="../../assets/css/modules/estudiante.css" />

  <style>
    body {
      font-family: "Inter", sans-serif;
      background: #f6f8fa;
      margin: 0;
      color: #2c3e50;
    }
    .main {
      max-width: 650px;
      margin: 4rem auto;
      padding: 0 1.5rem;
    }
    .card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
      overflow: hidden;
    }
    .card header {
      background: #f1f3f5;
      padding: 1rem 1.5rem;
      font-weight: 600;
      border-bottom: 1px solid #e5e7ea;
    }
    .content {
      padding: 1.5rem;
      text-align: center;
    }
    .warning-icon {
      font-size: 3rem;
      color: #e74c3c;
      margin-bottom: 1rem;
    }
    .alert-title {
      font-weight: 700;
      color: #c0392b;
      font-size: 1.3rem;
      margin-bottom: 0.5rem;
    }
    .alert-text {
      color: #555;
      font-size: 0.95rem;
      margin-bottom: 1.5rem;
    }
    .data-box {
      background: #fafafa;
      border: 1px solid #e1e1e1;
      border-radius: 6px;
      padding: 0.8rem 1.2rem;
      text-align: left;
      font-size: 0.95rem;
      margin-bottom: 1.5rem;
    }
    .data-box p {
      margin: 0.3rem 0;
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
    .actions {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-top: 1rem;
    }
    .btn {
      padding: 0.6rem 1.4rem;
      border-radius: 6px;
      text-decoration: none;
      font-size: 0.95rem;
      font-weight: 600;
      transition: all 0.2s ease;
      border: none;
      cursor: pointer;
    }
    .btn.danger {
      background: #e74c3c;
      color: #fff;
    }
    .btn.danger:hover {
      background: #c0392b;
    }
    .btn.secondary {
      background: #95a5a6;
      color: #fff;
    }
    .btn.secondary:hover {
      background: #7f8c8d;
    }
    .foot {
      text-align: center;
      color: #888;
      margin-top: 2rem;
      font-size: 0.9rem;
    }
  </style>
</head>

<body>
 <div class="app">  
         <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

  <main class="main">
    <section class="card">
      <header>🗑️ Confirmar eliminación</header>
      <div class="content">
        <div class="warning-icon">⚠️</div>
        <p class="alert-title">¿Estás seguro de Desactivar este estudiante?</p>
        <p class="alert-text">Esta acción no podrá deshacerse. El estudiante y su relación con la empresa y convenio dejarán de estar activos en el sistema.</p>

        <div class="data-box">
          <p><strong>Nombre:</strong> Juan Carlos Pérez López</p>
          <p><strong>Matrícula:</strong> 20230145</p>
          <p><strong>Carrera:</strong> Ingeniería en Informática</p>
          <p><strong>Empresa:</strong> Barbería Gómez</p>
          <p><strong>Convenio:</strong> CVN-2025-001</p>
          <p><strong>Estatus:</strong> <span class="badge activo">Activo</span></p>
        </div>

        <div class="actions">
          <button class="btn danger">✅ Sí, Desactivar</button>
          <a href="estudiante_list.php" class="btn secondary">Cancelar</a>
        </div>
      </div>
    </section>

    <p class="foot">Área de Vinculación · Residencias Profesionales</p>
  </main>
</div>
</body>
</html>
