<?php
declare(strict_types=1);

require __DIR__ . '/../../handler/estudiante/estudiante_list_handler.php';
?>
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
      gap: 1rem;
      flex-wrap: wrap;
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
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
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
    .table-wrapper {
      overflow-x: auto;
    }
    .table {
      width: 100%;
      border-collapse: collapse;
      font-size: 0.95rem;
      min-width: 720px;
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
      flex-wrap: wrap;
    }
    .badge {
      padding: 4px 8px;
      border-radius: 6px;
      color: #fff;
      font-size: 0.85rem;
      display: inline-block;
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
    .alert {
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 1rem;
    }
    .alert.error {
      background: #fce8e6;
      color: #a50e0e;
      border: 1px solid #f8b4b4;
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
        <a href="estudiante_add.php" class="btn">➕ <span>Nuevo estudiante</span></a>
      </div>
    </header>

    <?php if (in_array('database_error', $viewErrors, true)): ?>
      <div class="alert error">
        ⚠️ Ocurrió un problema al consultar la base de datos. Inténtalo de nuevo más tarde.
      </div>
    <?php endif; ?>

    <!-- Filtros -->
    <section class="filters">
      <form class="filter-form" method="get">
        <label for="empresa_id">Empresa:</label>
        <select name="empresa_id" id="empresa_id">
          <option value="">Todas</option>
          <?php foreach ($empresas as $empresa): ?>
            <?php $empresaId = (int) ($empresa['id'] ?? 0); ?>
            <option value="<?php echo htmlspecialchars((string) $empresaId, ENT_QUOTES, 'UTF-8'); ?>" <?php echo ($empresaSeleccionada === $empresaId) ? 'selected' : ''; ?>>
              <?php echo htmlspecialchars((string) ($empresa['nombre'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
            </option>
          <?php endforeach; ?>
        </select>

        <label for="convenio_id">Convenio:</label>
        <select name="convenio_id" id="convenio_id">
          <option value="">Todos</option>
          <?php foreach ($convenios as $convenio): ?>
            <?php $convenioId = (int) ($convenio['id'] ?? 0); ?>
            <option value="<?php echo htmlspecialchars((string) $convenioId, ENT_QUOTES, 'UTF-8'); ?>" <?php echo ($convenioSeleccionado === $convenioId) ? 'selected' : ''; ?>>
              <?php
                $folio = (string) ($convenio['folio'] ?? 'Sin folio');
                $empresaConvenio = '';
                if (!empty($convenio['empresa_id'])) {
                    $empresaConvenio = sprintf(' · Empresa #%s', $convenio['empresa_id']);
                }
                echo htmlspecialchars($folio . $empresaConvenio, ENT_QUOTES, 'UTF-8');
              ?>
            </option>
          <?php endforeach; ?>
        </select>

        <button type="submit" class="btn secondary">🔍 <span>Filtrar</span></button>
        <?php if ($empresaSeleccionada !== null || $convenioSeleccionado !== null): ?>
          <a href="estudiante_list.php" class="btn secondary" style="background:#95a5a6;">✖ Limpiar</a>
        <?php endif; ?>
      </form>
    </section>

    <!-- Tabla -->
    <section class="card">
      <header>Listado general de estudiantes</header>
      <div class="content">
        <div class="table-wrapper">
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
              <?php if ($estudiantes === []): ?>
                <tr>
                  <td colspan="7" class="empty">No se encontraron estudiantes registrados.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($estudiantes as $estudiante): ?>
                  <?php $estudianteId = (int) ($estudiante['id'] ?? 0); ?>
                  <tr>
                    <td><?php echo htmlspecialchars((string) ($estudiante['nombre_completo'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars((string) ($estudiante['matricula'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars((string) ($estudiante['carrera'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars((string) ($estudiante['empresa_nombre'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars((string) ($estudiante['convenio_folio'] ?? 'Sin folio'), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><span class="<?php echo htmlspecialchars((string) ($estudiante['estatus_badge_class'] ?? 'badge'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string) ($estudiante['estatus_badge_label'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span></td>
                    <td class="actions-cell">
                      <a href="estudiante_view.php?id=<?php echo urlencode((string) $estudianteId); ?>" class="btn secondary">👁 Ver</a>
                      <a href="estudiante_edit.php?id=<?php echo urlencode((string) $estudianteId); ?>" class="btn">✏️ Editar</a>
                      <a href="estudiante_delete.php?id=<?php echo urlencode((string) $estudianteId); ?>" class="btn" style="background:#e74c3c;">🗑 Eliminar</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <p class="foot">Área de Vinculación · Residencias Profesionales</p>
  </main>
</div>
</body>
</html>
