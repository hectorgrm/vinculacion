<?php
declare(strict_types=1);

require_once __DIR__ . '/../handler/empresaconvenio_view_handler.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal Empresa · Convenio</title>

    <link rel="stylesheet" href="../assets/css/modules/convenioView.css" />
</head>
<body>

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
      <a href="index.php" class="btn">Inicio</a>
      <a href="../../common/logout.php" class="btn">Salir</a>
    </div>
  </header>

  <main class="container">

    <section class="titlebar">
      <div>
        <h1>Convenio con la Universidad</h1>
        <p>Consulta tu convenio vigente, descarga el PDF y revisa tus datos principales.</p>
      </div>
      <div class="actions">
        <?php if ($documentAvailable && isset($convenioData['document_url'])): ?>
          <a href="<?= htmlspecialchars((string) $convenioData['document_url']) ?>" class="btn" target="_blank">
            📄 <?= htmlspecialchars($convenioData['document_label'] ?? 'Documento del convenio') ?>
          </a>
        <?php else: ?>
          <span class="btn disabled">📄 Documento no disponible</span>
        <?php endif; ?>
      </div>
    </section>

    <?php if (in_array('database_error', $viewErrors, true)): ?>
      <div class="strip dangerbox">
        <strong>⚠️ Ocurrió un problema al consultar la información.</strong>
        <span>Algunos datos podrían no mostrarse. Intenta nuevamente más tarde.</span>
      </div>
    <?php endif; ?>

    <section class="grid">
      <div class="col">
        <div class="card">
          <header>Datos del convenio</header>
          <div class="content">
            <?php if ($hasConvenio && $convenioData !== null): ?>
              <div class="summary">
                <div class="row"><strong>Folio:</strong> <span><?= htmlspecialchars((string) $convenioData['folio_label']) ?></span></div>
                <div class="row"><strong>Tipo de convenio:</strong> <span><?= htmlspecialchars((string) $convenioData['tipo_label']) ?></span></div>
                <div class="row"><strong>Responsable académico:</strong> <span><?= htmlspecialchars((string) $convenioData['responsable_label']) ?></span></div>
                <div class="row"><strong>Inicio:</strong> <span><?= htmlspecialchars((string) $convenioData['fecha_inicio_label']) ?></span></div>
                <div class="row"><strong>Fin:</strong> <span><?= htmlspecialchars((string) $convenioData['fecha_fin_label']) ?></span></div>
                <div class="row">
                  <strong>Estatus:</strong>
                  <span><span class="badge <?= htmlspecialchars($statusMeta['badge_variant']) ?>"><?= htmlspecialchars($statusMeta['badge_label']) ?></span></span>
                </div>
              </div>
            <?php else: ?>
              <p class="empty">Aún no se ha registrado un convenio para tu empresa.</p>
            <?php endif; ?>

            <div class="strip <?= htmlspecialchars($statusMeta['strip_variant']) ?>">
              <strong><?= htmlspecialchars($statusMeta['strip_title']) ?></strong>
              <span><?= htmlspecialchars($statusMeta['strip_message']) ?></span>
            </div>

            <?php if ($hasConvenio && $convenioData !== null): ?>
              <?php if (($convenioData['observaciones_plain'] ?? '') !== ''): ?>
                <div class="note-block">
                  <strong>Observaciones</strong>
                  <p><?= nl2br(htmlspecialchars((string) $convenioData['observaciones_plain'])) ?></p>
                </div>
              <?php else: ?>
                <p class="note muted">Sin observaciones registradas.</p>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>

        <div class="card" id="pdf">
          <header>Convenio (PDF)</header>
          <div class="content">
            <?php if ($documentAvailable && isset($convenioData['document_embed_url'])): ?>
              <div class="pdf-frame">
                <iframe src="<?= htmlspecialchars((string) $convenioData['document_embed_url']) ?>" title="Convenio PDF"></iframe>
              </div>
              <div class="file-actions">
                <a class="btn" href="<?= htmlspecialchars((string) $convenioData['document_url']) ?>" target="_blank">📄 Abrir en nueva pestaña</a>
                <a class="btn" download href="<?= htmlspecialchars((string) $convenioData['document_url']) ?>">⬇️ Descargar PDF</a>
              </div>
              <small class="note">
                <?php if (($convenioData['document_source'] ?? null) === 'firmado'): ?>
                  Se muestra el documento firmado registrado en la plataforma.
                <?php elseif (($convenioData['document_source'] ?? null) === 'borrador'): ?>
                  Se muestra el borrador más reciente cargado para el convenio.
                <?php else: ?>
                  Documento disponible para consulta.
                <?php endif; ?>
              </small>
            <?php else: ?>
              <p class="empty">No hay un archivo PDF disponible actualmente.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>

      
      <div class="col">
        <div class="card contactos">
  <header>Contactos de Residencias</header>
  <div class="content contact-grid">

    <!-- Vinculación -->
    <div class="contact">
      <div class="icon-box">
        📞
      </div>
      <div class="info">
        <h4>Responsable de Vinculación</h4>
        <p>Ing. <strong>Mariana López</strong></p>
        <p><a href="mailto:vinculacion@universidad.mx">vinculacion@universidad.mx</a></p>
        <p>Tel. (33) 5555 0001 · L–V 9:00–15:00</p>
      </div>
    </div>

    <!-- Área Jurídica -->
    <div class="contact">
      <div class="icon-box">
        ⚖️
      </div>
      <div class="info">
        <h4>Área Jurídica</h4>
        <p>Lic. <strong>Carlos Ruiz</strong></p>
        <p><a href="mailto:juridico@universidad.mx">juridico@universidad.mx</a></p>
        <p>Tel. (33) 5555 0002 · L–V 9:00–15:00</p>
      </div>
    </div>

    <!-- Residencias Profesionales -->
    <div class="contact">
      <div class="icon-box">
        🎓
      </div>
      <div class="info">
        <h4>Área de Residencias Profesionales</h4>
        <p><a href="mailto:residencias@universidad.mx">residencias@universidad.mx</a></p>
        <p>Tel. (33) 5555 0003 · L–V 9:00–15:00</p>
      </div>
    </div>

  </div>
</div>
        <div class="card contactos">
          <header>Contacto de la empresa</header>
          <div class="content contact-grid">
            <?php if ($empresaData !== null): ?>
              <div class="contact">
                <h4>Contacto principal</h4>
                <?php if (($empresaData['contacto_nombre'] ?? '') !== ''): ?>
                  <p><strong><?= htmlspecialchars((string) $empresaData['contacto_nombre']) ?></strong></p>
                <?php else: ?>
                  <p class="muted">No registrado.</p>
                <?php endif; ?>
                <?php if (($empresaData['contacto_email'] ?? '') !== ''): ?>
                  <p><a href="mailto:<?= htmlspecialchars((string) $empresaData['contacto_email']) ?>"><?= htmlspecialchars((string) $empresaData['contacto_email']) ?></a></p>
                <?php else: ?>
                  <p class="muted">Correo no registrado.</p>
                <?php endif; ?>
                <?php if (($empresaData['telefono'] ?? '') !== ''): ?>
                  <p>Tel. <?= htmlspecialchars((string) $empresaData['telefono']) ?></p>
                <?php else: ?>
                  <p class="muted">Teléfono no registrado.</p>
                <?php endif; ?>
              </div>

              <div class="contact">
                <h4>Representante</h4>
                <?php if (($empresaData['representante'] ?? '') !== ''): ?>
                  <p><strong><?= htmlspecialchars((string) $empresaData['representante']) ?></strong></p>
                <?php else: ?>
                  <p class="muted">No registrado.</p>
                <?php endif; ?>
                <?php if (($empresaData['cargo_representante'] ?? '') !== ''): ?>
                  <p><?= htmlspecialchars((string) $empresaData['cargo_representante']) ?></p>
                <?php else: ?>
                  <p class="muted">Cargo no registrado.</p>
                <?php endif; ?>
              </div>

              <div class="contact">
                <h4>Canales adicionales</h4>
                <?php if (($empresaData['sitio_web_url'] ?? null) !== null): ?>
                  <p><a href="<?= htmlspecialchars((string) $empresaData['sitio_web_url']) ?>" target="_blank"><?= htmlspecialchars((string) $empresaData['sitio_web_label']) ?></a></p>
                <?php else: ?>
                  <p class="muted">Sitio web no registrado.</p>
                <?php endif; ?>
                <p><?= htmlspecialchars((string) $empresaData['direccion_label']) ?></p>
              </div>
            <?php else: ?>
              <p class="empty">No se registró información de contacto.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </section>

    <section class="card historial">
  <header>Historial de convenios de la empresa</header>
  <div class="content">
    <?php if (!empty($listaConvenios)): ?>
      <table class="table">
        <thead>
          <tr>
            <th>Folio</th>
            <th>Versión</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Estatus</th>
            <th>Origen</th>
            <th>Documento</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($listaConvenios as $c): ?>
            <?php $isActive = ($c['id'] ?? 0) === ($convenioData['id'] ?? 0); ?>
            <tr class="<?= $isActive ? 'active-row' : '' ?>">
              <td><?= htmlspecialchars($c['folio'] ?? '—') ?></td>
              <td><?= htmlspecialchars($c['version_label'] ?? '—') ?></td>
              <td><?= htmlspecialchars($c['fecha_inicio_label'] ?? '—') ?></td>
              <td><?= htmlspecialchars($c['fecha_fin_label'] ?? '—') ?></td>
              <td>
                <span class="badge <?= htmlspecialchars($c['estatus_variant'] ?? 'secondary') ?>">
                  <?= htmlspecialchars($c['estatus_label'] ?? 'Sin estatus') ?>
                </span>
              </td>
              <td>
                <span class="muted">
                  <?= htmlspecialchars($c['origen_label'] ?? 'Convenio principal') ?>
                </span>
              </td>
              <td>
                <?php if (!empty($c['document_url'])): ?>
                  <a href="<?= htmlspecialchars((string) $c['document_url']) ?>" target="_blank">📄 Ver</a>
                <?php else: ?>
                  <span class="muted">No disponible</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($isActive): ?>
                  <span class="muted">Mostrando</span>
                <?php else: ?>
                  <a class="btn small" href="?id_convenio=<?= urlencode((string) $c['id']) ?>">🔍 Ver detalle</a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="empty">No hay convenios registrados para esta empresa.</p>
    <?php endif; ?>
  </div>
</section>


    <p class="foot">Portal de Empresa · Universidad · Área de Residencias</p>
  </main>

</body>
</html>
