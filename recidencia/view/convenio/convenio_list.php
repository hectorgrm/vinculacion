<?php

declare(strict_types=1);

require_once __DIR__ . '/../../controller/ConvenioController.php';
require_once __DIR__ . '/../../common/functions/conveniofunction.php';

use Residencia\Controller\ConvenioController;

$search = isset($_GET['search']) ? trim((string) $_GET['search']) : '';
$rawStatus = isset($_GET['estatus']) ? trim((string) $_GET['estatus']) : '';

$estatusFilter = $rawStatus !== '' ? convenioNormalizeStatus($rawStatus) : null;
$selectedStatus = $estatusFilter ?? '';

$convenios = [];
$errorMessage = null;

try {
  $controller = new ConvenioController();
  $convenios = $controller->listConvenios($search !== '' ? $search : null, $estatusFilter);
} catch (Throwable $exception) {
  $errorMessage = $exception->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Convenios · Residencias Profesionales</title>
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/convenios/convenio_list.css" />
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>📑 Gestión de Convenios</h2>
          <p class="subtitle">Controla los convenios activos, en revisión y vencidos con las empresas vinculadas.</p>
        </div>
        <a href="convenio_add.php" class="btn primary">➕ Nuevo Convenio</a>
      </header>

      <!-- FILTROS -->
      <section class="card">
        <header>🔍 Filtros y búsqueda</header>
        <div class="content">
          <form method="GET" class="form search-bar">
            <input type="text" name="search" placeholder="Buscar por empresa, folio o versión..."
              value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>" />
            <select name="estatus">
              <option value="">Todos los estados</option>
              <?php foreach (convenioStatusOptions() as $option): ?>
                <option value="<?php echo htmlspecialchars($option, ENT_QUOTES, 'UTF-8'); ?>"
                  <?php echo $selectedStatus === $option ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($option, ENT_QUOTES, 'UTF-8'); ?>
                </option>
              <?php endforeach; ?>
            </select>
            <button type="submit" class="btn primary">Buscar</button>
            <a href="convenio_list.php" class="btn secondary">Limpiar</a>
          </form>
        </div>
      </section>

      <!-- LISTA -->
      <section class="card">
        <header>📋 Convenios registrados</header>
        <div class="content">
          <?php if ($errorMessage !== null): ?>
            <div class="alert error" role="alert"
              style="margin-bottom:16px; padding:12px 16px; border-radius:8px; background:#fce8e6; color:#a50e0e;">
              <?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Empresa</th>
                  <th>Folio</th>
                  <th>Estatus</th>
                  <th>Machote</th>
                  <th>Versión</th>
                  <th>Inicio</th>
                  <th>Fin</th>
                  <th>Actualizado</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($convenios === []): ?>
                  <tr>
                    <td colspan="10" style="text-align:center;">No se encontraron convenios con los filtros aplicados.</td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($convenios as $convenio): ?>
                    <tr>
                      <td><?php echo htmlspecialchars((string) ($convenio['id'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars(convenioValueOrDefault($convenio['empresa_nombre'] ?? null), ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars(convenioValueOrDefault($convenio['folio'] ?? null), ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><span class="<?php echo convenioRenderBadgeClass($convenio['estatus'] ?? null); ?>">
                          <?php echo htmlspecialchars(convenioRenderBadgeLabel($convenio['estatus'] ?? null), ENT_QUOTES, 'UTF-8'); ?>
                        </span></td>
                      <td><?php echo htmlspecialchars(convenioValueOrDefault($convenio['machote_version'] ?? null), ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars(convenioValueOrDefault($convenio['version_actual'] ?? null), ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars(convenioFormatDate($convenio['fecha_inicio'] ?? null), ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars(convenioFormatDate($convenio['fecha_fin'] ?? null), ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars(convenioFormatDateTime($convenio['actualizado_en'] ?? null), ENT_QUOTES, 'UTF-8'); ?></td>
                      <td class="actions">
                        <a href="convenio_view.php?id=<?php echo urlencode((string) ($convenio['id'] ?? '')); ?>" class="btn sm">👁️</a>
                        <a href="convenio_edit.php?id=<?php echo urlencode((string) ($convenio['id'] ?? '')); ?>" class="btn sm">✏️</a>
                        <a href="convenio_delete.php?id=<?php echo urlencode((string) ($convenio['id'] ?? '')); ?>" class="btn sm">🗑️</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- LEYENDA -->
      <div class="legend">
        <span class="badge ok">Activa</span>
        <span class="badge secondary">En revisión</span>
        <span class="badge warn">Inactiva</span>
        <span class="badge err">Suspendida</span>
      </div>
    </main>
  </div>
</body>

</html>
