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

  <style>
    :root{
      --primary:#1f6feb; --primary-600:#1656b8;
      --ink:#0f172a; --muted:#64748b;
      --panel:#ffffff; --bg:#f6f8fb; --border:#e5e7eb;
      --ok:#16a34a; --warn:#f59e0b; --danger:#e11d48;
      --radius:16px; --shadow:0 6px 20px rgba(2,6,23,.06);
    }
    *{box-sizing:border-box}
    body{margin:0;font-family:Inter,system-ui,Segoe UI,Roboto,Arial,sans-serif;background:var(--bg);color:var(--ink)}

    /* Header */
    .portal-header{
      background:#fff;border-bottom:1px solid var(--border);
      padding:14px 18px;display:flex;justify-content:space-between;align-items:center
    }
    .brand{display:flex;gap:12px;align-items:center}
    .logo{width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,var(--primary),#6ea8ff);box-shadow:0 2px 10px rgba(31,111,235,.25)}
    .userbox{display:flex;gap:8px;align-items:center}
    .userbox .company{font-weight:700;color:#334155}

    /* Utils */
    .btn{display:inline-block;border:1px solid var(--border);background:#fff;padding:10px 14px;border-radius:12px;text-decoration:none;color:var(--ink);font-weight:600;cursor:pointer}
    .btn:hover{background:#f8fafc}
    .btn.primary{background:var(--primary);border-color:var(--primary);color:#fff}
    .btn.primary:hover{background:var(--primary-600)}
    .btn.disabled{opacity:.55;pointer-events:none;cursor:not-allowed}
    .badge{display:inline-block;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:700}
    .badge.ok{background:#dcfce7;color:#166534}
    .badge.warn{background:#fff7ed;color:#9a3412;border:1px solid #fed7aa}
    .badge.danger{background:#fee2e2;color:#991b1b;border:1px solid #fecaca}

    /* Layout */
    .container{max-width:1200px;margin:20px auto;padding:0 14px}
    .titlebar{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:14px;flex-wrap:wrap}
    .titlebar h1{margin:0 0 4px 0;font-size:22px}
    .titlebar p{margin:0;color:var(--muted)}
    .grid{display:grid;grid-template-columns:1.25fr .75fr;gap:16px}
    @media (max-width: 1024px){.grid{grid-template-columns:1fr}}

    .card{background:var(--panel);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow)}
    .card header{padding:12px 14px;border-bottom:1px solid var(--border);font-weight:700}
    .card .content{padding:14px}

    /* Convenio data */
    .summary{display:grid;grid-template-columns:repeat(2,1fr);gap:10px}
    .summary .row{display:flex;gap:8px;align-items:flex-start}
    .summary .row strong{min-width:150px}
    .strip{display:flex;align-items:center;gap:10px;padding:10px 12px;border:1px solid var(--border);border-radius:12px;background:#f8fafc;margin-top:10px}
    .warnbox{border-color:#fed7aa;background:#fff7ed}
    .okbox{border-color:#bbf7d0;background:#ecfdf5}
    .dangerbox{border-color:#fecaca;background:#fee2e2}
    .empty{margin:0;color:var(--muted);background:#f8fafc;border:1px dashed var(--border);padding:12px;border-radius:12px}
    .note-block{margin-top:12px;padding:12px;border-radius:12px;background:#f8fafc;border:1px solid var(--border)}
    .note-block strong{display:block;margin-bottom:6px;color:#334155}
    .note-block p{margin:0;color:#334155}
    .muted{color:var(--muted)}

    /* PDF */
    .pdf-frame{border:1px solid var(--border);border-radius:12px;overflow:hidden}
    .pdf-frame iframe{width:100%;height:560px;border:0}
    .file-actions{display:flex;gap:10px;margin-top:10px;flex-wrap:wrap}
    .note{color:var(--muted);font-size:12px}

    /* Contacts */
    .contact-grid{display:grid;grid-template-columns:1fr;gap:10px}
    .contact{border:1px solid var(--border);border-radius:12px;padding:12px;background:#fff}
    .contact h4{margin:0 0 6px 0}
    .contact p{margin:4px 0;color:#334155}
    .contact a{color:var(--primary);text-decoration:none}
    .contact a:hover{text-decoration:underline}

    /* Footer */
    .foot{margin-top:16px;color:var(--muted);font-size:12px;text-align:center}
  </style>
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

    <p class="foot">Portal de Empresa · Universidad · Área de Residencias</p>
  </main>

</body>
</html>
