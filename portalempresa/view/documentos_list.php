<?php
declare(strict_types=1);

if (!isset($documentos)) {
    require __DIR__ . '/../handler/empresa_documento_list_handler.php';
    return;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal Empresa · Documentos</title>
  <link rel="stylesheet" href="../assets/css/documentos/documento_list.css" />
</head>
<body>

<?php
$empresaNombre = isset($empresaNombre) && $empresaNombre !== ''
    ? (string) $empresaNombre
    : 'Empresa';

/** @var array<int, array<string, mixed>> $documentos */
$documentos = isset($documentos) && is_array($documentos) ? $documentos : [];

/** @var array{aprobado: int, pendiente: int, rechazado: int} $kpis */
$kpis = isset($kpis) && is_array($kpis)
    ? array_merge(['aprobado' => 0, 'pendiente' => 0, 'rechazado' => 0], $kpis)
    : ['aprobado' => 0, 'pendiente' => 0, 'rechazado' => 0];

/** @var array{q: string, estatus: string} $filterValues */
$filterValues = isset($filterValues) && is_array($filterValues)
    ? array_merge(['q' => '', 'estatus' => ''], $filterValues)
    : ['q' => '', 'estatus' => ''];

/** @var array<string, string> $statusOptions */
$statusOptions = isset($statusOptions) && is_array($statusOptions)
    ? $statusOptions
    : empresaDocumentoStatusOptions();

$tiposDocumentos = isset($tiposDocumentos) && is_array($tiposDocumentos)
    ? $tiposDocumentos
    : [];

/** @var array{type: string, message: string}|null $uploadFlash */
$uploadFlash = isset($uploadFlash) && is_array($uploadFlash)
    ? $uploadFlash
    : null;

$errorMessage = isset($errorMessage) && $errorMessage !== '' ? (string) $errorMessage : null;

$kpiOk   = (int) ($kpis['aprobado'] ?? 0);
$kpiPend = (int) ($kpis['pendiente'] ?? 0);
$kpiRech = (int) ($kpis['rechazado'] ?? 0);

$hasUploadOptions = $tiposDocumentos !== [];
?>

<!-- ======================================================= -->
<!-- ENCABEZADO PORTAL -->
<!-- ======================================================= -->
<header class="portal-header">
  <div class="brand">
    <div class="logo"></div>
    <div>
      <strong>Portal de Empresa</strong><br>
      <small>Residencias Profesionales</small>
    </div>
  </div>
  <div class="userbox">
    <span class="company"><?= htmlspecialchars($empresaNombre) ?></span>
    <a href="portal_list.php" class="btn">Inicio</a>
    <a href="../../common/logout.php" class="btn">Salir</a>
  </div>
</header>

<!-- ======================================================= -->
<!-- CONTENIDO PRINCIPAL -->
<!-- ======================================================= -->
<main class="container">

  <section class="titlebar">
    <div>
      <h1>📂 Documentos de la empresa</h1>
      <p>Consulta los documentos solicitados y sube tu versión actualizada si fue requerida por el área de Vinculación.</p>
    </div>
    <div class="actions">
      <a href="convenio_view.php" class="btn">📑 Ver convenio</a>
    </div>
  </section>

  <section class="grid">
    <!-- COLUMNA IZQUIERDA -->
    <div class="col">
      <!-- KPIs -->
      <div class="card">
        <header>Resumen</header>
        <div class="content">
          <div class="kpis">
            <div class="kpi"><div class="num"><?= $kpiOk ?></div><div class="lbl">Aprobados</div></div>
            <div class="kpi"><div class="num"><?= $kpiPend ?></div><div class="lbl">Pendientes</div></div>
            <div class="kpi"><div class="num"><?= $kpiRech ?></div><div class="lbl">Rechazados</div></div>
          </div>
        </div>
      </div>

      <!-- LISTADO -->
      <div class="card">
        <header>Listado de documentos</header>
        <div class="content">

          <?php if ($errorMessage !== null): ?>
            <div class="alert error"><?= htmlspecialchars($errorMessage) ?></div>
          <?php endif; ?>

          <!-- FILTROS -->
          <form class="filters" method="get" action="">
            <div class="field">
              <label for="q">Buscar</label>
              <input
                type="text"
                id="q"
                name="q"
                placeholder="Nombre del documento…"
                value="<?= htmlspecialchars($filterValues['q']) ?>"
              >
            </div>
            <div class="field">
              <label for="estado">Estado</label>
              <select id="estado" name="estado">
                <option value="">Todos</option>
                <?php foreach ($statusOptions as $value => $label): ?>
                  <option value="<?= htmlspecialchars($value) ?>" <?= $filterValues['estatus'] === $value ? 'selected' : '' ?>>
                    <?= htmlspecialchars($label) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <button class="btn primary" type="submit">🔎 Filtrar</button>
          </form>

          <!-- TABLA -->
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Documento</th>
                  <th>Tipo</th>
                  <th>Estado</th>
                  <th>Última actualización</th>
                  <th>Observaciones</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($documentos === []): ?>
                  <tr>
                    <td colspan="6" class="empty">No se encontraron documentos con los filtros seleccionados.</td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($documentos as $d): ?>
                  <tr>
                    <td><?= htmlspecialchars((string) ($d['nombre_documento'] ?? 'Documento')) ?></td>
                    <td><?= htmlspecialchars((string) ($d['tipo'] ?? '')) ?></td>
                    <td>
                      <?php
                        $badgeClass = $d['badge_class'] ?? empresaDocumentoBadgeClass($d['estatus'] ?? null);
                        $badgeLabel = $d['estatus_label'] ?? empresaDocumentoBadgeLabel($d['estatus'] ?? null);
                      ?>
                      <span class="<?= htmlspecialchars($badgeClass) ?>"><?= htmlspecialchars($badgeLabel) ?></span>
                    </td>
                    <td><?= !empty($d['actualizado_en']) ? htmlspecialchars((string) $d['actualizado_en']) : '—' ?></td>
                    <td><?= isset($d['observaciones']) && $d['observaciones'] !== '' ? htmlspecialchars((string) $d['observaciones']) : '—' ?></td>
                    <td class="actions">
                      <?php if (!empty($d['archivo_path'])): ?>
                        <a class="btn small" href="../../recidencia/<?= htmlspecialchars((string) $d['archivo_path']) ?>" target="_blank">📄 Ver</a>
                        <a class="btn small" href="../../recidencia/<?= htmlspecialchars((string) $d['archivo_path']) ?>" download>⬇️ Descargar</a>
                      <?php else: ?>
                        <span class="hint">Sin archivo</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>

    <!-- COLUMNA DERECHA -->
    <div class="col">
      <div class="card" id="subir">
        <header>Subir / Actualizar documento</header>
        <div class="content">
          <p class="hint">Solo usa este formulario si Residencias te solicitó reemplazar un documento pendiente o rechazado.</p>

          <?php if ($uploadFlash !== null): ?>
            <div class="alert <?= $uploadFlash['type'] === 'success' ? 'success' : 'error' ?>">
              <?= htmlspecialchars($uploadFlash['message']) ?>
            </div>
          <?php endif; ?>

          <form class="upload" method="post" enctype="multipart/form-data" action="../handler/empresa_documento_upload_handler.php">
            <div class="field">
              <label for="doc_tipo">Tipo de documento</label>
              <select id="doc_tipo" name="doc_tipo" <?= $hasUploadOptions ? '' : 'disabled' ?> required>
                <option value="">Selecciona…</option>
                <?php foreach ($tiposDocumentos as $tipo): ?>
                  <?php
                    $optionValue = isset($tipo['value']) ? (string) $tipo['value'] : '';
                    if ($optionValue === '') {
                        continue;
                    }

                    $optionLabel = isset($tipo['label']) ? (string) $tipo['label'] : 'Documento';
                    $optionDisabled = !empty($tipo['disabled']);
                  ?>
                  <option value="<?= htmlspecialchars($optionValue) ?>" <?= $optionDisabled ? 'disabled' : '' ?>>
                    <?= htmlspecialchars($optionLabel) ?>
                  </option>
                <?php endforeach; ?>
                <?php if ($tiposDocumentos === []): ?>
                  <option value="" disabled>No hay documentos disponibles para subir.</option>
                <?php endif; ?>
              </select>
            </div>

            <div class="field">
              <label for="archivo">Archivo (PDF/JPG/PNG)</label>
              <input type="file" id="archivo" name="archivo" accept=".pdf,.jpg,.jpeg,.png" required>
            </div>

            <div class="field">
              <label for="comentario">Comentario (opcional)</label>
              <textarea id="comentario" name="comentario" rows="3" placeholder="Notas para el área de Vinculación…"></textarea>
            </div>

            <div class="actions">
              <button class="btn primary" type="submit" <?= $hasUploadOptions ? '' : 'disabled' ?>>⬆️ Subir documento</button>
              <a class="btn" href="#top">Cancelar</a>
            </div>

            <?php if (!$hasUploadOptions): ?>
              <p class="hint">Todos los documentos asignados están aprobados o no requieren cambios por ahora.</p>
            <?php endif; ?>
          </form>
        </div>
      </div>

      <div class="card">
        <header>Ayuda</header>
        <div class="content">
          <ul>
            <li>Formatos aceptados: PDF, JPG, PNG.</li>
            <li>Tamaño máximo recomendado: 10 MB.</li>
            <li>Revisa las observaciones antes de subir nuevamente un documento rechazado.</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

</main>

</body>
</html>
