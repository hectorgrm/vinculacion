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
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />

  <style>
    :root{
      --ok:#16a34a;
      --warn:#f59e0b;
      --err:#e11d48;
      --secondary:#64748b;
      --border:#e5e7eb;
      --bg:#fff;
      --shadow:0 4px 14px rgba(0,0,0,.04);
    }
    body{font-family:Inter,system-ui,sans-serif;margin:0;background:#f6f8fb;color:#0f172a}
    .badge{display:inline-block;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:700;color:#fff}
    .badge.ok{background:var(--ok)}
    .badge.warn{background:var(--warn)}
    .badge.err{background:var(--err)}
    .badge.secondary{background:var(--secondary)}
    .btn{display:inline-block;padding:8px 12px;border-radius:8px;text-decoration:none;border:1px solid var(--border);color:#0f172a;background:#fff}
    .btn.primary{background:#1f6feb;color:#fff;border:none}
    .btn.danger{background:#e11d48;color:#fff;border:none}
    .card{background:#fff;border:1px solid var(--border);border-radius:12px;box-shadow:var(--shadow);margin-bottom:20px}
    .card header{padding:12px 16px;border-bottom:1px solid var(--border);font-weight:700;font-size:16px}
    .card .content{padding:16px}
    table{width:100%;border-collapse:collapse;font-size:14px}
    th,td{padding:10px 8px;border-bottom:1px solid var(--border);text-align:left;vertical-align:middle}
    th{background:#f1f5f9;text-transform:uppercase;font-size:13px;color:#475569}
    .actions .btn{font-size:13px;padding:6px 10px}
    .topbar{display:flex;justify-content:space-between;align-items:center;padding:14px 18px;border-bottom:1px solid var(--border);background:#fff}
    .breadcrumb{font-size:13px;color:#64748b}
    .breadcrumb a{text-decoration:none;color:#1f6feb}
    .main{padding:18px}
    .legend{margin-top:10px;color:#64748b;font-size:13px;display:flex;gap:10px;flex-wrap:wrap;align-items:center}
    .filters{display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;margin-bottom:16px}
    .filters .field{display:flex;flex-direction:column;gap:6px;min-width:220px}
    .filters label{font-weight:600;color:#334155;font-size:13px}
    .filters input,.filters select{padding:8px 10px;border-radius:8px;border:1px solid var(--border);font-size:14px}
    .filters .actions{display:flex;gap:8px}
    .alert{padding:12px 16px;border-radius:8px;margin-bottom:16px;border:1px solid transparent}
    .alert.error{background:#fef2f2;color:#b91c1c;border-color:#fecaca}
    .table-wrapper{overflow:auto}
  </style>
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
