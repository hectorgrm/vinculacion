<?php

declare(strict_types=1);

$handlerResult = require __DIR__ . '/../../handler/auditoria/auditoria_list_handler.php';

$search = $handlerResult['q'];
$selectedActorTipo = $handlerResult['actor_tipo'];
$selectedActorId = $handlerResult['actor_id'];
$selectedAccion = $handlerResult['accion'];
$selectedEntidad = $handlerResult['entidad'];
$selectedFechaDesde = $handlerResult['fecha_desde'];
$selectedFechaHasta = $handlerResult['fecha_hasta'];
$auditorias = $handlerResult['auditorias'];
$actorTipoOptions = $handlerResult['actorTipoOptions'];
$errorMessage = $handlerResult['errorMessage'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Historial de Auditoría - Residencias Profesionales</title>

  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/modules/documentos.css" />

  <style>
    .filters {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      align-items: flex-end;
    }

    .filters .field {
      min-width: 180px;
      flex: 1;
    }

    .filters .field.short {
      max-width: 140px;
    }

    .filters .actions {
      display: flex;
      gap: 8px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      padding: 10px 12px;
      border-bottom: 1px solid #e0e0e0;
      text-align: left;
    }

    th {
      background: #f8f8f8;
      font-weight: 600;
    }

    .table-wrapper {
      overflow-x: auto;
    }

    .actor-type {
      font-weight: 600;
    }

    .actor-id {
      color: #555;
      font-size: 13px;
    }

    details {
      background: #fafafa;
      border: 1px solid #ececec;
      border-radius: 8px;
      padding: 8px 12px;
    }

    details summary {
      cursor: pointer;
      font-weight: 600;
      color: #333;
      outline: none;
    }

    .audit-details {
      margin: 8px 0 0 0;
      padding-left: 16px;
      list-style: none;
      display: grid;
      gap: 6px;
    }

    .audit-details li {
      display: grid;
      gap: 2px;
    }

    .audit-field {
      font-size: 13px;
      color: #666;
    }

    .audit-values {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      font-size: 13px;
    }

    .badge {
      display: inline-flex;
      align-items: center;
      padding: 4px 8px;
      border-radius: 999px;
      background: #eef2ff;
      color: #334155;
      font-size: 12px;
      font-weight: 600;
    }
  </style>
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>Historial de Auditoría</h2>
          <p class="subtitle">Consulta los eventos registrados en el sistema.</p>
        </div>
      </header>

      <section class="card">
        <header>Filtros de búsqueda</header>
        <div class="content">
          <form method="GET" class="form">
            <div class="filters">
              <div class="field">
                <label for="q">Buscar</label>
                <input type="text" id="q" name="q" placeholder="Acción, entidad, IP..."
                  value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="actor_tipo">Tipo de actor</label>
                <select id="actor_tipo" name="actor_tipo">
                  <option value="">Todos</option>
                  <?php foreach ($actorTipoOptions as $value => $label): ?>
                    <option value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>"
                      <?php echo $selectedActorTipo === $value ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="field short">
                <label for="actor_id">ID de actor</label>
                <input type="number" min="0" id="actor_id" name="actor_id"
                  value="<?php echo htmlspecialchars($selectedActorId, ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="accion">Acción</label>
                <input type="text" id="accion" name="accion" placeholder="Registrar, actualizar..."
                  value="<?php echo htmlspecialchars($selectedAccion, ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="entidad">Entidad</label>
                <input type="text" id="entidad" name="entidad" placeholder="Tabla o módulo"
                  value="<?php echo htmlspecialchars($selectedEntidad, ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field short">
                <label for="fecha_desde">Desde</label>
                <input type="date" id="fecha_desde" name="fecha_desde"
                  value="<?php echo htmlspecialchars($selectedFechaDesde, ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field short">
                <label for="fecha_hasta">Hasta</label>
                <input type="date" id="fecha_hasta" name="fecha_hasta"
                  value="<?php echo htmlspecialchars($selectedFechaHasta, ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="actions">
                <button type="submit" class="btn primary">Buscar</button>
                <a class="btn" href="auditoria_list.php">Limpiar</a>
              </div>
            </div>
          </form>
        </div>
      </section>

      <section class="card">
        <header>Eventos registrados</header>
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
                  <th>Actor</th>
                  <th>Acción</th>
                  <th>Entidad</th>
                  <th>Entidad ID</th>
                  <th>IP</th>
                  <th>Fecha</th>
                  <th>Detalles</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($auditorias === []): ?>
                  <tr>
                    <td colspan="7" style="text-align:center;">No se encontraron eventos con los filtros aplicados.</td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($auditorias as $evento): ?>
                    <?php
                    $auditoriaId = isset($evento['id']) ? (int) $evento['id'] : 0;
                    $actorTipo = $evento['actor_tipo'] ?? null;
                    $actorId = $evento['actor_id'] ?? null;
                    $accion = auditoriaValueOrDefault($evento['accion'] ?? null);
                    $entidad = auditoriaValueOrDefault($evento['entidad'] ?? null);
                    $entidadId = auditoriaValueOrDefault($evento['entidad_id'] ?? null, 'N/A');
                    $ip = auditoriaValueOrDefault($evento['ip'] ?? null, 'N/A');
                    $fecha = auditoriaFormatDateTime($evento['ts'] ?? null);
                    $actorTipoLabel = auditoriaActorTipoLabel(is_string($actorTipo) ? $actorTipo : null);
                    $actorLabel = auditoriaValueOrDefault(
                      $evento['actor_label'] ?? ($evento['actor_nombre'] ?? null),
                      $actorTipoLabel
                    );
                    $actorIdLabel = auditoriaValueOrDefault($actorId, 'Sin referencia');
                    $actorMeta = array_values(array_filter([
                      $actorTipoLabel,
                      $actorIdLabel !== 'Sin referencia' ? 'ID: ' . $actorIdLabel : null,
                    ]));
                    $actorMetaLabel = $actorMeta !== [] ? implode(' · ', $actorMeta) : '';
                    ?>
                    <tr>
                      <td><?php echo htmlspecialchars((string) $auditoriaId, ENT_QUOTES, 'UTF-8'); ?></td>
                      <td>
                        <div class="actor-type"><?php echo htmlspecialchars($actorLabel, ENT_QUOTES, 'UTF-8'); ?></div>
                        <?php if ($actorMetaLabel !== ''): ?>
                          <div class="actor-id"><?php echo htmlspecialchars($actorMetaLabel, ENT_QUOTES, 'UTF-8'); ?></div>
                        <?php endif; ?>
                      </td>
                      <td><?php echo htmlspecialchars($accion, ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars($entidad, ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars($entidadId, ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars($ip, ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars($fecha, ENT_QUOTES, 'UTF-8'); ?></td>
                      <td>
                        <?php $detalles = $evento['detalles'] ?? []; ?>
                        <?php if (is_array($detalles) && $detalles !== []): ?>
                          <details>
                            <summary>
                              <span class="badge">Ver cambios (<?php echo count($detalles); ?>)</span>
                            </summary>
                            <ul class="audit-details">
                              <?php foreach ($detalles as $detalle): ?>
                                <li>
                                  <span class="audit-field"><?php echo htmlspecialchars((string) ($detalle['campo_label'] ?? $detalle['campo'] ?? 'Campo'), ENT_QUOTES, 'UTF-8'); ?></span>
                                  <div class="audit-values">
                                    <span><strong>Antes:</strong> <?php echo htmlspecialchars(auditoriaValueOrDefault($detalle['valor_anterior'] ?? null, '—'), ENT_QUOTES, 'UTF-8'); ?></span>
                                    <span><strong>Ahora:</strong> <?php echo htmlspecialchars(auditoriaValueOrDefault($detalle['valor_nuevo'] ?? null, '—'), ENT_QUOTES, 'UTF-8'); ?></span>
                                  </div>
                                </li>
                              <?php endforeach; ?>
                            </ul>
                          </details>
                        <?php else: ?>
                          <span class="badge" style="background:#f1f5f9; color:#475569;">Sin cambios</span>
                        <?php endif; ?>
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
