<?php
require_once __DIR__ . '/../../handler/machote/machote_list_handler.php';

$totalMachotes = is_array($machotes ?? null) ? count($machotes) : 0;
$searchTerm = $search ?? '';
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

    <main class="main machote-view">

      <header class="hero">
        <div class="hero__text">
          <p class="eyebrow">Plantillas y revisiones</p>
          <div class="hero__headline">
            <h1>Machotes de Convenio</h1>
            <span class="pill"><?= $totalMachotes; ?> registrados</span>
          </div>
          <p class="subtitle">
            Administra versiones institucionales, revisiones activas con empresas y acciones rápidas sobre cada machote.
          </p>
        </div>
        <div class="hero__actions">
          <a href="machote_add.php" class="btn primary">Nuevo machote</a>
        </div>
      </header>

      <section class="card">
        <header>Búsqueda y listado</header>
        <div class="content">
          <form class="filters" method="get">
            <label class="filters__field">
              <span>Buscar</span>
              <div class="filters__control">
                <input
                  type="text"
                  name="search"
                  placeholder="Buscar por empresa o versión..."
                  value="<?= htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8'); ?>"
                />
              </div>
            </label>
            <div class="filters__actions">
              <button type="submit" class="btn primary">Buscar</button>
              <a href="machote_list.php" class="btn secondary">Limpiar</a>
            </div>
          </form>

          <div class="table-wrapper">
            <?php if (!empty($machotes)) : ?>
              <table>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Empresa</th>
                    <th>Versión</th>
                    <th>Fecha</th>
                    <th>Estatus</th>
                    <th style="min-width:220px;">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($machotes as $index => $machote) : ?>
                    <?php
                      $empresaNombre = htmlspecialchars($machote['empresa'] ?? '-', ENT_QUOTES, 'UTF-8');
                      $empresaId = isset($machote['empresa_id']) ? (int) $machote['empresa_id'] : null;
                    ?>
                    <tr>
                      <td><?= $index + 1; ?></td>
                      <td>
                        <?php if ($empresaId !== null && $empresaId > 0): ?>
                          <a class="btn small secondary" href="../empresa/empresa_view.php?id=<?= urlencode((string)$empresaId); ?>">
                            <?= $empresaNombre; ?>
                          </a>
                        <?php else: ?>
                          <?= $empresaNombre; ?>
                        <?php endif; ?>
                      </td>
                      <td><?= htmlspecialchars($machote['version'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?= htmlspecialchars($machote['fecha'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?= $machote['badge'] ?? '<span class="badge gris">&mdash;</span>'; ?></td>
                      <td class="actions">
                        <a href="machote_revisar.php?id=<?= urlencode((string)($machote['id'] ?? '')); ?>" class="btn small primary">Revisar comentarios</a>
                        <a href="machote_edit.php?id=<?= urlencode((string)($machote['id'] ?? '')); ?>" class="btn small">Editar</a>
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
