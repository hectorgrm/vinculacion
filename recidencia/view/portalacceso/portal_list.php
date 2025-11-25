<?php
declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/portalacceso/portalacceso_list_functions.php';

/** @var array{
 *     search: string,
 *     status: string,
 *     statusOptions: array<string, string>,
 *     portales: array<int, array<string, mixed>>,
 *     errorMessage: ?string
 * } $handlerResult
 */
$handlerResult = require __DIR__ . '/../../handler/portalacceso/portal_list_handler.php';

$search = $handlerResult['search'];
$status = $handlerResult['status'];
$statusOptions = $handlerResult['statusOptions'];
$portales = $handlerResult['portales'];
$errorMessage = $handlerResult['errorMessage'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Portal de Acceso · Residencias Profesionales</title>

  <!-- Estilos globales -->
  <link rel="stylesheet" href="../../assets/css/modules/portalacceso.css" />


</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <div>
          <h2>🔐 Portales de Acceso</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a> › Portal de Acceso
          </nav>
        </div>
        <a class="btn primary" href="portal_add.php">➕ Crear acceso</a>
      </header>

      <!-- Listado -->
      <section class="card">
        <header>📋 Accesos Registrados</header>
        <div class="content">
          <?php if ($errorMessage !== null): ?>
            <div class="alert error" role="alert">
              <?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <form class="filters" method="get">
            <div class="field">
              <label for="search">Buscar:</label>
              <input type="text" id="search" name="search" placeholder="Empresa, token o NIP..." value="<?php echo htmlspecialchars((string) $search, ENT_QUOTES, 'UTF-8'); ?>" />
            </div>
            <div class="field" style="max-width:220px;">
              <label for="status">Estatus:</label>
              <select id="status" name="status">
                <?php foreach ($statusOptions as $value => $label): ?>
                  <option value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $status === $value ? 'selected' : ''; ?>><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="actions">
              <button type="submit" class="btn primary">🔍 Buscar</button>
              <a class="btn" href="portal_list.php">Limpiar</a>
            </div>
          </form>

          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Empresa</th>
                  <th>Token</th>
                  <th>NIP</th>
                  <th>Estatus</th>
                  <th>Expira</th>
                  <th>Creado en</th>
                  <th style="min-width:250px;">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($portales === []): ?>
                  <tr>
                    <td colspan="8" style="text-align:center;">No se encontraron accesos registrados.</td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($portales as $portal): ?>
                    <?php
                      $portalId = (string) ($portal['id'] ?? '');
                      $empresaId = (string) ($portal['empresa_id'] ?? '');
                      $empresaNombre = (string) ($portal['empresa_nombre'] ?? '');
                      $empresaNumeroControl = (string) ($portal['empresa_numero_control'] ?? '');
                      $token = (string) ($portal['token'] ?? '');
                      $nip = portalAccessFormatNip(isset($portal['nip']) ? (string) $portal['nip'] : null);
                      $statusCode = (string) ($portal['status'] ?? '');
                      $statusLabel = portalAccessStatusLabel($statusCode);
                      $statusClass = portalAccessStatusBadgeClass($statusCode);
                      $expiracion = portalAccessFormatDateTime($portal['expiracion'] ?? null);
                      $creadoEn = portalAccessFormatDateTime($portal['creado_en'] ?? null);
                      $isActive = (string) ($portal['activo'] ?? '0') === '1';
                      $toggleTo = $isActive ? '0' : '1';
                      $toggleLabel = $isActive ? '🚫 Desactivar' : '✅ Activar';
                      $empresaLabel = $empresaNombre;

                      if ($empresaNumeroControl !== '') {
                        $empresaLabel .= ' · ' . $empresaNumeroControl;
                      }
                    ?>
                    <tr>
                      <td><?php echo htmlspecialchars($portalId, ENT_QUOTES, 'UTF-8'); ?></td>
                      <td>
                        <?php if ($empresaId !== ''): ?>
                          <a class="btn" href="../empresa/empresa_view.php?id=<?php echo urlencode($empresaId); ?>">
                            <?php echo htmlspecialchars($empresaLabel, ENT_QUOTES, 'UTF-8'); ?>
                          </a>
                        <?php else: ?>
                          <?php echo htmlspecialchars($empresaLabel !== '' ? $empresaLabel : '—', ENT_QUOTES, 'UTF-8'); ?>
                        <?php endif; ?>
                      </td>
                      <td><code><?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?></code></td>
                      <td><?php echo htmlspecialchars($nip, ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><span class="<?php echo $statusClass; ?>"><?php echo htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8'); ?></span></td>
                      <td><?php echo htmlspecialchars($expiracion, ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars($creadoEn, ENT_QUOTES, 'UTF-8'); ?></td>
                      <td class="actions" style="display:flex;gap:6px;flex-wrap:wrap;">
                        <a class="btn" href="portal_view.php?id=<?php echo urlencode($portalId); ?>">👁️ Ver</a>
                        <a class="btn" href="portal_edit.php?id=<?php echo urlencode($portalId); ?>">✏️ Editar</a>
                        <a class="btn" href="portal_toggle.php?id=<?php echo urlencode($portalId); ?>&amp;to=<?php echo urlencode($toggleTo); ?>"><?php echo $toggleLabel; ?></a>
                        <a class="btn danger" href="portal_delete.php?id=<?php echo urlencode($portalId); ?>">🗑️ Eliminar</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <div class="legend">
            <strong>Leyenda:</strong>
            <span class="badge ok">Activo</span>
            <span class="badge warn">Inactivo</span>
            <span class="badge err">Expirado</span>
          </div>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
