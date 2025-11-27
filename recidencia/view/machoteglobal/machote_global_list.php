<?php
if (!isset($machotes)) {
    require __DIR__ . '/../../handler/machoteglobal/machote_global_list_handler.php';
    return;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Machote Global | Versiones Institucionales</title>

  <link rel="stylesheet" href="../../assets/css/modules/machoteglobal/machotegloballist.css" />
</head>
<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <div class="topbar">
        <div>
          <h2>Machote Global | Versiones Institucionales</h2>
          <p class="subtitle">Administra las versiones del machote institucional (plantilla base).</p>
        </div>
        <div class="actions">
          <a href="machote_edit.php" class="btn primary">Nuevo machote global</a>
          <a href="machote_list.php" class="btn secondary">Ir a machotes por empresa</a>
        </div>
      </div>

      <section class="card">
        <div class="content">
          <form class="filters" method="get" onsubmit="return false;">
            <input type="text" name="q" placeholder="Buscar por versión o descripción…" />
            <select name="estado">
              <option value="">Estado: Todos</option>
              <option value="vigente">Vigente</option>
              <option value="borrador">Borrador</option>
              <option value="archivado">Archivado</option>
            </select>
            <button class="btn primary">Buscar</button>
            <a class="btn secondary" href="machote_global_list.php">Limpiar</a>
          </form>

          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th style="width:64px">#</th>
                  <th>Versión</th>
                  <th>Estado</th>
                  <th>Descripción</th>
                  <th>Creado</th>
                  <th>Actualizado</th>
                  <th style="min-width:140px">Vista</th>
                  <th style="min-width:280px">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($machotes)) : ?>
                  <tr>
                    <td colspan="8" class="empty-state">
                      No se encontraron machotes registrados.
                    </td>
                  </tr>
                <?php else : ?>
                  <?php foreach ($machotes as $index => $machote) :
                      $id = (int)($machote['id'] ?? 0);
                      $version = htmlspecialchars((string)($machote['version'] ?? ''), ENT_QUOTES, 'UTF-8');
                      $estado = (string)($machote['estado'] ?? 'borrador');
                      $descripcion = trim((string)($machote['descripcion'] ?? ''));
                      $descripcion = $descripcion !== ''
                          ? nl2br(htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8'))
                          : '<span class="muted">Sin descripción</span>';
                      $creado = machote_global_format_datetime($machote['creado_en'] ?? null);
                      $actualizado = machote_global_format_datetime($machote['actualizado_en'] ?? null);
                      $badgeClass = machote_global_estado_badge($estado);
                      $estadoLabel = machote_global_estado_label($estado);
                  ?>
                    <tr>
                      <td><?php echo $index + 1; ?></td>
                      <td><strong><?php echo $version !== '' ? $version : '-'; ?></strong></td>
                      <td><span class="<?php echo $badgeClass; ?>"><?php echo $estadoLabel; ?></span></td>
                      <td><?php echo $descripcion; ?></td>
                      <td><?php echo $creado; ?></td>
                      <td><?php echo $actualizado; ?></td>
                      <td>
                        <a
                          href="../../view/machoteglobal/machote_view.php?id=<?php echo $id; ?>"
                          class="btn small secondary"
                          title="Ver machote #<?php echo $id; ?> en modo lectura"
                        >Ver</a>
                      </td>
                      <td class="actions">
                        <a href="machote_edit.php?id=<?php echo $id; ?>" class="btn small">Editar</a>
                        <a href="machote_revisar.php?id=<?php echo $id; ?>" class="btn small">Ver revisiones</a>
                        <button class="btn small" type="button" title="Duplicar registro #<?php echo $id; ?>">Duplicar</button>
                        <button class="btn small danger" type="button" title="Archivar registro #<?php echo $id; ?>">Archivar</button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
