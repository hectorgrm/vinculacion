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
  <link rel="stylesheet" href="../assets/css/modules/documento_list.css" />
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

/** @var array{total: int, subidos: int, aprobados: int, porcentaje: int} $documentosStats */
$documentosStats = isset($documentosStats) && is_array($documentosStats)
    ? array_merge(['total' => 0, 'subidos' => 0, 'aprobados' => 0, 'porcentaje' => 0], $documentosStats)
    : ['total' => 0, 'subidos' => 0, 'aprobados' => 0, 'porcentaje' => 0];
$progresoGeneral = (int) ($documentosStats['porcentaje'] ?? 0);

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
$portalReadOnly = !empty($portalReadOnly);
$portalReadOnlyMessage = isset($portalReadOnlyMessage) ? (string) $portalReadOnlyMessage : null;

/** @var array{type: string, message: string}|null $uploadFlash */
$uploadFlash = isset($uploadFlash) && is_array($uploadFlash)
    ? $uploadFlash
    : null;

$errorMessage = isset($errorMessage) && $errorMessage !== '' ? (string) $errorMessage : null;

$kpiOk   = (int) ($kpis['aprobado'] ?? 0);
$kpiPend = (int) ($kpis['pendiente'] ?? 0);
$kpiRech = (int) ($kpis['rechazado'] ?? 0);

$hasUploadOptions = $tiposDocumentos !== [];
$uploadDisabled = $portalReadOnly || !$hasUploadOptions;
?>

<!-- ENCABEZADO PORTAL -->
<?php include __DIR__ . '/../layout/portal_empresa_header.php'; ?>

<?php if ($uploadFlash !== null): ?>
  <?php
    $uploadType = $uploadFlash['type'] === 'success' ? 'success' : 'error';
    $uploadTitle = $uploadType === 'success'
      ? 'Documento enviado'
      : 'No pudimos procesar el archivo';
  ?>
  <section class="flash-bar">
    <div class="alert toast <?= $uploadType ?>" data-autohide="true" role="status">
      <div class="alert__icon" aria-hidden="true"><?= $uploadType === 'success' ? '&#10003;' : '!' ?></div>
      <div class="alert__body">
        <p class="alert__title"><?= $uploadTitle ?></p>
        <p class="alert__message"><?= htmlspecialchars($uploadFlash['message']) ?></p>
      </div>
      <button class="alert__close" type="button" aria-label="Cerrar alerta">&times;</button>
    </div>
  </section>
<?php endif; ?>

<!-- CONTENIDO PRINCIPAL -->
<main class="container">
  <?php if ($portalReadOnly): ?>
    <section class="flash-bar">
      <div class="alert toast warn" role="status">
        <div class="alert__icon" aria-hidden="true">!</div>
        <div class="alert__body">
          <p class="alert__title">Portal en modo lectura</p>
          <p class="alert__message"><?= htmlspecialchars($portalReadOnlyMessage ?? 'Solo puedes consultar la informacion del portal mientras la empresa esta en modo de solo lectura.') ?></p>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <section class="titlebar">
    <div>
      <h1>Documentos de la empresa</h1>
      <p>Consulta los documentos solicitados y sube tu version actualizada si fue requerida por el area de Vinculacion.</p>
    </div>
    <div class="actions">
      <a href="convenio_view.php" class="btn">Ver convenio</a>
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
          <div class="docs-progress">
            <div class="progress-heading">
              <span class="progress-label">Progreso general</span>
              <span class="progress-value"><?= $progresoGeneral ?>%</span>
            </div>
            <div class="progress-track">
              <div class="progress-fill" style="width:<?= $progresoGeneral ?>%;"></div>
            </div>
            <small class="progress-meta"><?= $progresoGeneral ?>% completado</small>
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
                placeholder="Nombre del documento..."
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
            <button class="btn primary" type="submit">Filtrar</button>
          </form>

          <!-- TABLA -->
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Documento</th>
                  <th>Tipo</th>
                  <th>Estado</th>
                  <th>Ultima actualizacion</th>
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
                        <a class="btn small" href="../../recidencia/<?= htmlspecialchars((string) $d['archivo_path']) ?>" target="_blank">Ver</a>
                        <a class="btn small" href="../../recidencia/<?= htmlspecialchars((string) $d['archivo_path']) ?>" download>Descargar</a>
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
          <p class="hint">Solo usa este formulario si Residencias te solicito reemplazar un documento pendiente o rechazado.</p>
          <?php if ($portalReadOnly): ?>
            <div class="alert warn" style="margin-top:8px;"><?= htmlspecialchars($portalReadOnlyMessage ?? 'No es posible subir archivos mientras el portal esta en modo solo lectura.') ?></div>
          <?php endif; ?>

          <form class="upload" method="post" enctype="multipart/form-data" action="../handler/empresa_documento_upload_handler.php">
            <div class="field">
              <label for="doc_tipo">Tipo de documento</label>
              <select id="doc_tipo" name="doc_tipo" <?= $uploadDisabled ? 'disabled' : '' ?> required>
                <option value="">Selecciona</option>
                <?php foreach ($tiposDocumentos as $tipo): ?>
                  <?php
                    $optionValue = isset($tipo['value']) ? (string) $tipo['value'] : '';
                    if ($optionValue === '') {
                        continue;
                    }

                    $optionLabel = isset($tipo['label']) ? (string) $tipo['label'] : 'Documento';
                    $optionDisabled = !empty($tipo['disabled']) || $portalReadOnly;
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
              <input type="file" id="archivo" name="archivo" accept=".pdf,.jpg,.jpeg,.png" required <?= $uploadDisabled ? 'disabled' : '' ?>>
            </div>

            <div class="field">
              <label for="comentario">Comentario (opcional)</label>
              <textarea id="comentario" name="comentario" rows="3" placeholder="Notas para el area de Vinculacion..." <?= $uploadDisabled ? 'disabled' : '' ?>></textarea>
            </div>

            <div class="actions">
              <button class="btn primary" type="submit" <?= $uploadDisabled ? 'disabled' : '' ?>>Subir documento</button>
              <a class="btn" href="#top">Cancelar</a>
            </div>

            <?php if (!$hasUploadOptions && !$portalReadOnly): ?>
              <p class="hint">Todos los documentos asignados estan aprobados o no requieren cambios por ahora.</p>
            <?php elseif ($portalReadOnly): ?>
              <p class="hint"><?= htmlspecialchars($portalReadOnlyMessage ?? 'Las cargas de documentos estan deshabilitadas porque la empresa esta en modo solo lectura.') ?></p>
            <?php endif; ?>
          </form>
        </div>
      </div>

      <div class="card">
        <header>Ayuda</header>
        <div class="content">
          <ul>
            <li>Formatos aceptados: PDF, JPG, PNG.</li>
            <li>Tamano maximo recomendado: 10 MB.</li>
            <li>Revisa las observaciones antes de subir nuevamente un documento rechazado.</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

</main>

<script>
(function () {
  // Limpia los parametros de estado para evitar que el mensaje reaparezca al refrescar.
  try {
    var currentUrl = new URL(window.location.href);
    var removed = false;
    ['status', 'doc', 'reason'].forEach(function (param) {
      if (currentUrl.searchParams.has(param)) {
        currentUrl.searchParams.delete(param);
        removed = true;
      }
    });
    if (removed && window.history && window.history.replaceState) {
      var newSearch = currentUrl.searchParams.toString();
      var newUrl = currentUrl.pathname + (newSearch ? '?' + newSearch : '') + currentUrl.hash;
      window.history.replaceState({}, document.title, newUrl);
    }
  } catch (err) {
    // Ignora errores de navegadores antiguos.
  }

  var flash = document.querySelector('.alert.toast[data-autohide]');
  if (!flash) { return; }

  var closeBtn = flash.querySelector('.alert__close');
  var hide = function () {
    if (flash.classList.contains('is-closing')) { return; }
    flash.classList.add('is-closing');
    var onDone = function () {
      flash.removeEventListener('transitionend', onDone);
      if (flash.parentNode) {
        flash.parentNode.removeChild(flash);
      }
    };
    flash.addEventListener('transitionend', onDone);
    setTimeout(onDone, 600);
  };

  var timer = setTimeout(hide, 5000);

  if (closeBtn) {
    closeBtn.addEventListener('click', function () {
      clearTimeout(timer);
      hide();
    });
  }
})();
</script>

</body>
</html>
