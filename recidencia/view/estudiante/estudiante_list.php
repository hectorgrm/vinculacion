<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Estudiantes · Residencia Profesional</title>

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
      max-width: 1200px;
      margin: 2rem auto;
      padding: 0 1.5rem;
    }
    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }
    .topbar h2 {
      margin: 0;
    }
    .subtitle {
      font-size: 0.95rem;
      color: #7f8c8d;
    }
    .btn {
      background: #0055aa;
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      text-decoration: none;
      font-size: 0.9rem;
      border: none;
      cursor: pointer;
      transition: background 0.2s ease;
    }
    .btn:hover { background: #00408a; }
    .btn.secondary { background: #7f8c8d; }
    .btn.secondary:hover { background: #5f6d70; }
    .card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
      overflow: hidden;
      margin-bottom: 1.5rem;
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
    .filters {
      background: #f7f9fc;
      padding: 1rem 1.5rem;
      border-radius: 8px;
      margin-bottom: 1.5rem;
      border: 1px solid #e1e4e8;
    }
    .filter-form {
      display: flex;
      align-items: center;
      gap: 1rem;
      flex-wrap: wrap;
    }
    .filter-form label {
      font-weight: 600;
      color: #2d3436;
    }
    .filter-form select {
      padding: 6px 8px;
      border-radius: 6px;
      border: 1px solid #ccc;
      min-width: 160px;
      font-size: 0.95rem;
    }
    .table {
      width: 100%;
      border-collapse: collapse;
      font-size: 0.95rem;
    }
    .table th, .table td {
      padding: 10px 12px;
      border-bottom: 1px solid #e6e6e6;
      text-align: left;
    }
    .table th {
      background: #f2f4f6;
      font-weight: 600;
      color: #2c3e50;
    }
    .table tr:hover {
      background: #f9fbfd;
    }
    .actions-cell {
      display: flex;
      gap: 0.4rem;
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
    .empty {
      text-align: center;
      color: #7f8c8d;
      font-style: italic;
      padding: 1rem;
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

    <!-- Encabezado -->
    <header class="topbar">
      <div>
        <h2>👨‍🎓 Estudiantes · Residencia Profesional</h2>
        <p class="subtitle">Consulta, filtra y gestiona los estudiantes registrados por empresa o convenio.</p>
      </div>
      <div class="actions">
        <a href="rp_estudiante_add.php" class="btn">➕ Nuevo estudiante</a>
      </div>
    </header>

    <!-- Filtros -->
    <section class="filters">
      <form class="filter-form">
        <label>Empresa:</label>
        <select name="empresa_id">
          <option value="">Todas</option>
          <option>Barbería Gómez</option>
          <option>Homero Burgers</option>
          <option>Estética Lupita</option>
        </select>

        <label>Convenio:</label>
        <select name="convenio_id">
          <option value="">Todos</option>
          <option>CVN-2025-001</option>
          <option>CVN-2025-002</option>
          <option>CVN-2025-003</option>
        </select>

        <button type="submit" class="btn secondary">🔍 Filtrar</button>
      </form>
    </section>

    <!-- Tabla -->
    <section class="card">
      <header>Listado general de estudiantes</header>
      <div class="content">
        <table class="table">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Matrícula</th>
              <th>Carrera</th>
              <th>Empresa</th>
              <th>Convenio</th>
              <th>Estatus</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Juan Carlos Pérez López</td>
              <td>20230145</td>
              <td>Ing. Informática</td>
              <td>Barbería Gómez</td>
              <td>CVN-2025-001</td>
              <td><span class="badge activo">Activo</span></td>
              <td class="actions-cell">
                <a href="estudiante_view.php" class="btn secondary">👁 Ver</a>
                <a href="estudiante_edit.php" class="btn">✏️ Editar</a>
                <a href="estudiante_delete.php" class="btn danger" style="background:#e74c3c;">🗑 Eliminar</a>
              </td>
            </tr>
            <tr>
              <td>Ana Lucía Torres Díaz</td>
              <td>20220489</td>
              <td>Ing. Industrial</td>
              <td>Homero Burgers</td>
              <td>CVN-2025-002</td>
              <td><span class="badge finalizado">Finalizado</span></td>
              <td class="actions-cell">
                <a href="rp_estudiante_view.php" class="btn secondary">👁 Ver</a>
                <a href="rp_estudiante_edit.php" class="btn">✏️ Editar</a>
                <a href="estudiante_delete.php.php" class="btn danger" style="background:#e74c3c;">🗑 Eliminar</a>
              </td>
            </tr>
            <tr>
              <td>Mario Alberto Ruiz Santos</td>
              <td>20220891</td>
              <td>Ing. Electrónica</td>
              <td>Estética Lupita</td>
              <td>CVN-2025-003</td>
              <td><span class="badge inactivo">Inactivo</span></td>
              <td class="actions-cell">
                <a href="rp_estudiante_view.php" class="btn secondary">👁 Ver</a>
                <a href="rp_estudiante_edit.php" class="btn">✏️ Editar</a>
                <a href="estudiante_delete.php" class="btn danger" style="background:#e74c3c;">🗑 Eliminar</a>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Si no hay registros -->
        <!-- <p class="empty">No hay estudiantes registrados actualmente.</p> -->
      </div>
    </section>

    <p class="foot">Área de Vinculación · Residencias Profesionales</p>
  </main>
</div>
</body>
</html>
