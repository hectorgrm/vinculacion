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

  <link rel="stylesheet" href="../../assets/css/estudiantes/estudiante_list.css" />


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
                      <a href="estudiante_deactivate.php?id=<?php echo urlencode((string) $estudianteId); ?>" class="btn" style="background:#e74c3c;">🗑 Desactivar</a>
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
