<?php

declare(strict_types=1);

$handlerResult = require __DIR__ . '/../../handler/documento/documento_list_handler.php';

$search = $handlerResult['q'];
$selectedEmpresa = $handlerResult['empresa'];
$selectedTipo = $handlerResult['tipo'];
$selectedStatus = $handlerResult['estatus'];
$documentos = $handlerResult['documentos'];
$empresas = $handlerResult['empresas'];
$tipos = $handlerResult['tipos'];
$statusOptions = $handlerResult['statusOptions'];
$errorMessage = $handlerResult['errorMessage'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Documentos | Residencias Profesionales</title>
  <link rel="stylesheet" href="../../assets/css/modules/documentos/documento.css" />
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>Gestión de Documentos</h2>
          <p class="subtitle">Revisión, control y estado de los documentos cargados por las empresas.</p>
        </div>
        <div class="actions">
          <a href="documento_upload.php" class="btn primary">Subir Documento</a>
        </div>
      </header>

      <section class="card">
        <header>Filtros de búsqueda</header>
        <div class="content">
          <form method="GET" class="form">
            <div class="filters">
              <div class="field">
                <label for="q">Buscar</label>
                <input id="q" name="q" type="text" placeholder="Empresa, tipo o nota..."
                  value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>" />
              </div>

              <div class="field">
                <label for="empresa">Empresa</label>
                <select id="empresa" name="empresa">
                  <option value="">Todas</option>
                  <?php foreach ($empresas as $empresa): ?>
                    <?php
                    $empresaId = isset($empresa['id']) ? (string) $empresa['id'] : '';
                    $empresaNombre = documentoValueOrDefault($empresa['nombre'] ?? null);
                    ?>
                    <option value="<?php echo htmlspecialchars($empresaId, ENT_QUOTES, 'UTF-8'); ?>"
                      <?php echo $selectedEmpresa === $empresaId ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($empresaNombre, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="field">
                <label for="tipo">Tipo de Documento</label>
                <select id="tipo" name="tipo">
                  <option value="">Todos</option>
                  <?php foreach ($tipos as $tipo): ?>
                    <?php
                    $tipoId = isset($tipo['id']) ? (string) $tipo['id'] : '';
                    $tipoNombre = documentoValueOrDefault($tipo['nombre'] ?? null);
                    ?>
                    <option value="<?php echo htmlspecialchars($tipoId, ENT_QUOTES, 'UTF-8'); ?>"
                      <?php echo $selectedTipo === $tipoId ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($tipoNombre, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="field">
                <label for="estatus">Estatus</label>
                <select id="estatus" name="estatus">
                  <option value="">Todos</option>
                  <?php foreach ($statusOptions as $value => $label): ?>
                    <option value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>"
                      <?php echo $selectedStatus === $value ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="actions">
                <button type="submit" class="btn primary">Buscar</button>
                <a href="documento_list.php" class="btn secondary">Limpiar</a>
              </div>
            </div>
          </form>
        </div>
      </section>

      <section class="card">
        <header>Documentos registrados</header>
        <div class="content">
          <?php if ($errorMessage !== null): ?>
            <div class="alert error" role="alert">
              <?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Empresa</th>
                  <th>Tipo de Documento</th>
                  <th>Estatus</th>
                  <th>Observación</th>
                  <th>Fecha de carga</th>
                  <th style="min-width:260px;">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($documentos === []): ?>
                  <tr>
                    <td colspan="7" class="empty-state">No se encontraron documentos con los filtros aplicados.</td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($documentos as $documento): ?>
                    <?php
                    $documentoId = isset($documento['id']) ? (int) $documento['id'] : 0;
                    $empresaId = isset($documento['empresa_id']) ? (int) $documento['empresa_id'] : 0;
                    $empresaNombre = documentoValueOrDefault($documento['empresa_nombre'] ?? null);
                    $tipoNombre = documentoValueOrDefault($documento['tipo_nombre'] ?? null);
                    $estatus = $documento['estatus'] ?? '';
                    $badgeClass = documentoRenderBadgeClass($estatus);
                    $badgeLabel = documentoRenderBadgeLabel($estatus);
                    $observacion = documentoValueOrDefault($documento['observacion'] ?? null, 'Sin observaciones');
                    $fechaCarga = documentoFormatDateTime($documento['creado_en'] ?? null);
                    $ruta = $documento['ruta'] ?? null;
                    $archivoUrl = null;

                    if (is_string($ruta)) {
                        $ruta = trim($ruta);

                        if ($ruta !== '') {
                            $archivoUrl = '../../' . ltrim(str_replace(['\\'], '/', $ruta), '/');
                        }
                    }
                    ?>
                    <tr>
                      <td><?php echo htmlspecialchars((string) $documentoId, ENT_QUOTES, 'UTF-8'); ?></td>
                      <td>
                        <?php if ($empresaId > 0): ?>
                          <a class="btn small secondary" href="../empresa/empresa_view.php?id=<?php echo urlencode((string) $empresaId); ?>">
                            <?php echo htmlspecialchars($empresaNombre, ENT_QUOTES, 'UTF-8'); ?>
                          </a>
                        <?php else: ?>
                          <?php echo htmlspecialchars($empresaNombre, ENT_QUOTES, 'UTF-8'); ?>
                        <?php endif; ?>
                      </td>
                      <td><?php echo htmlspecialchars($tipoNombre, ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><span class="<?php echo htmlspecialchars($badgeClass, ENT_QUOTES, 'UTF-8'); ?>">
                          <?php echo htmlspecialchars($badgeLabel, ENT_QUOTES, 'UTF-8'); ?>
                        </span></td>
                      <td><?php echo htmlspecialchars($observacion, ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars($fechaCarga, ENT_QUOTES, 'UTF-8'); ?></td>
                      <td class="actions">
                        <?php if ($archivoUrl !== null): ?>
                          <a class="btn small" href="<?php echo htmlspecialchars($archivoUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">Ver</a>
                        <?php endif; ?>
                        <a class="btn small secondary" href="documento_view.php?id=<?php echo urlencode((string) $documentoId); ?>">Detalle</a>
                        <?php if ($estatus !== 'aprobado'): ?>
                          <a class="btn small primary" href="documento_review.php?id=<?php echo urlencode((string) $documentoId); ?>">Revisar</a>
                        <?php endif; ?>
                        <a class="btn small danger" href="documento_delete.php?id=<?php echo urlencode((string) $documentoId); ?>">Eliminar</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <div class="legend">
            <strong>Leyenda:</strong>
            <span class="badge ok">Aprobado</span>
            <span class="badge warn">Pendiente</span>
            <span class="badge err">Rechazado</span>
          </div>
        </div>
      </section>
    </main>
  </div>
</body>

</html>
