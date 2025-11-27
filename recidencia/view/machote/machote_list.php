<?php
require_once __DIR__ . '/../../handler/machote/machote_list_handler.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Machotes | Residencia Profesional</title>
  <link rel="stylesheet" href="../../assets/css/modules/machote/machotelist.css" />
</head>
<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">

      <header class="topbar">
        <div>
          <h2>Machotes de Convenio</h2>
          <p class="subtitle">
            Administra las versiones de machotes institucionales y revisiones activas con empresas.
          </p>
        </div>
        <div class="actions">
          <a href="machote_add.php" class="btn primary">Nuevo machote</a>
        </div>
      </header>

      <section class="card">
        <div class="content">
          <form class="search-bar" method="get">
            <input
              type="text"
              name="search"
              placeholder="Buscar por empresa o versión..."
              value="<?= htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8'); ?>"
            />
            <button type="submit" class="btn primary">Buscar</button>
            <a href="machote_list.php" class="btn secondary">Limpiar</a>
          </form>

          <div class="table-wrapper">
            <?php if (!empty($machotes)) : ?>
              <table>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Empresa</th>
                    <th>Versión Machote</th>
                    <th>Fecha</th>
                    <th>Estatus</th>
                    <th style="min-width:220px;">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($machotes as $index => $machote) : ?>
                    <tr>
                      <td><?= $index + 1; ?></td>
                      <td><?= htmlspecialchars($machote['empresa'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?= htmlspecialchars($machote['version'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?= htmlspecialchars($machote['fecha'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?= $machote['badge'] ?? '<span class="badge gris">-</span>'; ?></td>
                      <td class="actions">
                        <a href="machote_revisar.php?id=<?= urlencode((string)($machote['id'] ?? '')); ?>" class="btn small primary">Revisar</a>
                        <a href="machote_edit.php?id=<?= urlencode((string)($machote['id'] ?? '')); ?>" class="btn small">Editar</a>
                        <a href="machote_view.php?id=<?= urlencode((string)($machote['id'] ?? '')); ?>" class="btn small secondary">Ver</a>
                        <a
                          href="machote_delete.php?id=<?= urlencode((string)($machote['id'] ?? '')); ?>"
                          class="btn small danger"
                          onclick="return confirm('¿Eliminar este machote?');"
                        >Eliminar</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php else : ?>
              <div class="empty-state">
                <p><?= htmlspecialchars($error ?? 'No hay machotes disponibles.', ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
