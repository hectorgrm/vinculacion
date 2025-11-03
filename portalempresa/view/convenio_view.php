<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal Empresa · Convenio</title>

  <!-- HTML + CSS JUNTOS -->
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
    .btn.small{padding:8px 10px;font-size:13px}
    .badge{display:inline-block;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:700}
    .badge.ok{background:#dcfce7;color:#166534}
    .badge.warn{background:#fff7ed;color:#9a3412;border:1px solid #fed7aa}
    .badge.danger{background:#fee2e2;color:#991b1b;border:1px solid #fecaca}

    /* Layout */
    .container{max-width:1200px;margin:20px auto;padding:0 14px}
    .titlebar{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:14px}
    .titlebar h1{margin:0 0 4px 0;font-size:22px}
    .titlebar p{margin:0;color:var(--muted)}
    .grid{display:grid;grid-template-columns:1.25fr .75fr;gap:16px}
    @media (max-width: 1024px){.grid{grid-template-columns:1fr}}

    .card{background:var(--panel);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow)}
    .card header{padding:12px 14px;border-bottom:1px solid var(--border);font-weight:700}
    .card .content{padding:14px}

    /* Convenio data */
    .summary{display:grid;grid-template-columns:repeat(2,1fr);gap:10px}
    .summary .row{display:flex;gap:8px}
    .summary .row strong{min-width:130px}
    .strip{display:flex;align-items:center;gap:10px;padding:10px 12px;border:1px solid var(--border);border-radius:12px;background:#f8fafc;margin-top:10px}
    .warnbox{border-color:#fed7aa;background:#fff7ed}
    .okbox{border-color:#bbf7d0;background:#ecfdf5}
    .dangerbox{border-color:#fecaca;background:#fee2e2}

    /* PDF */
    .pdf-frame{border:1px solid var(--border);border-radius:12px;overflow:hidden}
    .pdf-frame iframe{width:100%;height:560px;border:0}
    .file-actions{display:flex;gap:10px;margin-top:10px;flex-wrap:wrap}
    .note{color:var(--muted);font-size:12px}

    /* Annex & contacts */
    .list{display:flex;flex-direction:column;gap:8px;margin:0;padding:0;list-style:none}
    .list a{text-decoration:none;color:var(--primary)}
    .list a:hover{text-decoration:underline}

    .contact-grid{display:grid;grid-template-columns:1fr;gap:10px}
    .contact{border:1px solid var(--border);border-radius:12px;padding:12px;background:#fff}
    .contact h4{margin:0 0 6px 0}
    .contact p{margin:0;color:var(--muted)}

    /* Renewal form (cta) */
    .cta{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
    .cta .hint{color:var(--muted);font-size:12px}

    /* Footer */
    .foot{margin-top:16px;color:var(--muted);font-size:12px;text-align:center}
  </style>
</head>
<body>

<?php
// Datos de ejemplo (sustituye por datos reales de la BD)
$empresaNombre  = 'Casa del Barrio';
$folioConvenio  = 'CV-2025-012';
$version        = 'Institucional v1.2';
$inicio         = '2025-06-01';
$fin            = '2026-05-30';
$estatus        = 'Aprobado'; // 'Aprobado' | 'Por vencer' | 'Vencido'

// Para demo: marcar advertencia si restan <60 días (simulado)
$alerta = ($estatus === 'Por vencer');
?>

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

<main class="container">

  <section class="titlebar">
    <div>
      <h1>Convenio con la Universidad</h1>
      <p>Consulta tu convenio vigente, descarga el PDF y revisa anexos y contactos.</p>
    </div>
    <div class="actions">
      <a href="../portalempresa/machote_view_aprobado.php" class="btn">📄 Documento final</a>
    </div>
  </section>

  <section class="grid">
    <!-- Columna principal: Detalle + PDF -->
    <div class="col">
      <div class="card">
        <header>Datos del convenio</header>
        <div class="content">
          <div class="summary">
            <div class="row"><strong>Folio:</strong> <span><?= htmlspecialchars($folioConvenio) ?></span></div>
            <div class="row"><strong>Versión:</strong> <span><?= htmlspecialchars($version) ?></span></div>
            <div class="row"><strong>Inicio:</strong> <span><?= htmlspecialchars($inicio) ?></span></div>
            <div class="row"><strong>Fin:</strong> <span><?= htmlspecialchars($fin) ?></span></div>
            <div class="row">
              <strong>Estatus:</strong>
              <span>
                <?php if($estatus==='Aprobado'): ?>
                  <span class="badge ok">Aprobado</span>
                <?php elseif($estatus==='Por vencer'): ?>
                  <span class="badge warn">Por vencer</span>
                <?php else: ?>
                  <span class="badge danger">Vencido</span>
                <?php endif; ?>
              </span>
            </div>
          </div>

          <?php if($estatus==='Por vencer'): ?>
            <div class="strip warnbox">
              <strong>⚠ Tu convenio está por vencer.</strong>
              <span>Te recomendamos iniciar la solicitud de renovación.</span>
            </div>
          <?php elseif($estatus==='Vencido'): ?>
            <div class="strip dangerbox">
              <strong>⛔ Convenio vencido.</strong>
              <span>Ponte en contacto con Residencias para renovarlo cuanto antes.</span>
            </div>
          <?php else: ?>
            <div class="strip okbox">
              <strong>✅ Convenio activo.</strong>
              <span>Todo en orden.</span>
            </div>
          <?php endif; ?>

        </div>
      </div>

      <div class="card" id="pdf">
        <header>Convenio (PDF)</header>
        <div class="content">
          <div class="pdf-frame">
            <!-- Cambia la ruta al PDF real del convenio -->
            <iframe src="../../uploads/convenio_vigente.pdf#view=FitH" title="Convenio PDF"></iframe>
          </div>
          <div class="file-actions">
            <a class="btn" href="../../uploads/convenio_vigente.pdf" target="_blank">📄 Abrir en nueva pestaña</a>
            <a class="btn" download href="../../uploads/convenio_vigente.pdf">⬇️ Descargar PDF</a>
          </div>
          <small class="note">Si no ves el PDF aquí, usa “Abrir en nueva pestaña” o “Descargar”.</small>
        </div>
      </div>
    </div>

    <!-- Columna lateral: Anexos, Contactos y Renovación -->
    <div class="col">
      <div class="card">
        <header>Anexos</header>
        <div class="content">
          <ul class="list">
            <li><a href="../../uploads/anexo_confidencialidad.pdf" target="_blank">🔒 Confidencialidad</a></li>
            <li><a href="../../uploads/anexo_propiedad_intelectual.pdf" target="_blank">🧠 Propiedad Intelectual</a></li>
            <li><a href="../../uploads/anexo_seguridad.pdf" target="_blank">🛡️ Seguridad e ingreso</a></li>
          </ul>
        </div>
      </div>

      <div class="card">
        <header>Contactos de Residencias</header>
        <div class="content contact-grid">
          <div class="contact">
            <h4>Responsable de Vinculación</h4>
            <p>Ing. Mariana López · <a href="mailto:vinculacion@uni.mx">vinculacion@uni.mx</a></p>
            <p>Tel. (33) 5555 0001 · 9:00–15:00</p>
          </div>
          <div class="contact">
            <h4>Área Jurídica</h4>
            <p>Lic. Carlos Ruiz · <a href="mailto:juridico@uni.mx">juridico@uni.mx</a></p>
            <p>Tel. (33) 5555 0002 · 9:00–15:00</p>
          </div>
        </div>
      </div>

      <div class="card" id="renovar">
        <header>Renovación</header>
        <div class="content">
          <p>¿Necesitas renovar o extender la vigencia del convenio?</p>
          <div class="cta">
            <a class="btn primary" href="convenio_solicitar_renovacion.php">↺ Solicitar renovación</a>
            <span class="hint">Tu solicitud será enviada a Residencias para seguimiento.</span>
          </div>
        </div>
      </div>

    </div>
  </section>

  <p class="foot">Portal de Empresa · Universidad · Área de Residencias</p>
</main>

</body>
</html>
