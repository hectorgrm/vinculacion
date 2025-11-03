<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal Empresa · Perfil</title>

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
    .portal-header{background:#fff;border-bottom:1px solid var(--border);padding:14px 18px;display:flex;justify-content:space-between;align-items:center}
    .brand{display:flex;gap:12px;align-items:center}
    .logo{width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,var(--primary),#6ea8ff);box-shadow:0 2px 10px rgba(31,111,235,.25)}
    .userbox{display:flex;gap:8px;align-items:center}
    .userbox .company{font-weight:700;color:#334155}
    .btn{display:inline-block;border:1px solid var(--border);background:#fff;padding:10px 14px;border-radius:12px;text-decoration:none;color:var(--ink);font-weight:600;cursor:pointer}
    .btn:hover{background:#f8fafc}.btn.primary{background:var(--primary);border-color:var(--primary);color:#fff}
    .btn.primary:hover{background:var(--primary-600)}
    .btn.small{padding:8px 10px;font-size:13px}

    /* Layout */
    .container{max-width:1200px;margin:20px auto;padding:0 14px}
    .titlebar{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:14px}
    .titlebar h1{margin:0 0 4px 0;font-size:22px}
    .titlebar p{margin:0;color:var(--muted)}

    .grid{display:grid;grid-template-columns:1.25fr .9fr;gap:16px}
    @media (max-width: 1024px){.grid{grid-template-columns:1fr}}

    .card{background:var(--panel);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow)}
    .card header{padding:12px 14px;border-bottom:1px solid var(--border);font-weight:700}
    .card .content{padding:14px}

    /* Perfil empresa */
    .profile-head{display:grid;grid-template-columns:auto 1fr auto;gap:14px;align-items:center}
    .avatar{width:64px;height:64px;border-radius:14px;background:linear-gradient(135deg,var(--primary),#6ea8ff)}
    .meta small{color:var(--muted)}
    .badge{display:inline-block;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:700}
    .badge.ok{background:#dcfce7;color:#166534}
    .badge.warn{background:#fff7ed;color:#9a3412;border:1px solid #fed7aa}

    .info-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:10px;margin-top:10px}
    @media (max-width:720px){.info-grid{grid-template-columns:1fr}}
    .row{display:flex;gap:8px}
    .row strong{min-width:180px;color:#334155}

    /* Contactos */
    .contact-grid{display:grid;grid-template-columns:1fr;gap:10px}
    .contact{border:1px solid var(--border);border-radius:12px;padding:12px;background:#fff}
    .contact h4{margin:0 0 6px 0}
    .contact p{margin:0;color:var(--muted)}
    .chips{display:flex;gap:8px;flex-wrap:wrap;margin-top:6px}
    .chip{border:1px solid var(--border);border-radius:999px;padding:4px 10px;font-size:12px;background:#f8fafc}

    /* Preferencias (solo lectura) */
    .prefs{display:grid;grid-template-columns:1fr 1fr;gap:10px}
    @media (max-width:720px){.prefs{grid-template-columns:1fr}}
    .pref{border:1px solid var(--border);border-radius:12px;padding:12px;display:flex;justify-content:space-between;align-items:center;background:#fff}
    .pref label{color:#334155;font-weight:600}
    .toggle{opacity:.65}

    /* Soporte */
    .help li{margin:6px 0;color:#334155}
    .help a{color:var(--primary);text-decoration:none}
    .help a:hover{text-decoration:underline}

    .foot{margin-top:16px;color:var(--muted);font-size:12px;text-align:center}
  </style>
</head>
<body>

<?php
// Datos de ejemplo (sustituye por BD/sesión)
$empresaNombre = 'Casa del Barrio';
$estatus       = 'Activa'; // Activa | En revisión | Suspendida
$rfc           = 'CDB810101AA1';
$giro          = 'Asociación Civil';
$folioConvenio = 'CV-2025-012';
$vigencia      = '2025-06-01 — 2026-05-30';
$domicilio     = 'Calle Primavera 123, Guadalajara, Jal. C.P. 44100';
$sitio         = 'https://casadelbarrio.mx';

// Contactos
$responsableVinc = ['nombre'=>'Ing. Mariana López','correo'=>'vinculacion@uni.mx','tel'=>'(33) 5555 0001','horario'=>'9:00–15:00'];
$juridico        = ['nombre'=>'Lic. Carlos Ruiz','correo'=>'juridico@uni.mx','tel'=>'(33) 5555 0002','horario'=>'9:00–15:00'];

// Preferencias (solo lectura visual)
$prefs = [
  ['label'=>'Avisos por correo electrónico','value'=>true],
  ['label'=>'Recordatorios de vigencia','value'=>true],
  ['label'=>'Notificaciones de documentos','value'=>true],
  ['label'=>'Boletín de residencias','value'=>false],
];
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
      <h1>Perfil de la empresa</h1>
      <p>Consulta tu información registrada y contactos de la universidad.</p>
    </div>
    <div class="actions">
      <a href="convenio_view.php" class="btn">📑 Ver convenio</a>
    </div>
  </section>

  <section class="grid">
    <!-- Columna principal -->
    <div class="col">
      <div class="card">
        <header>Datos generales</header>
        <div class="content">
          <div class="profile-head">
            <div class="avatar" aria-hidden="true"></div>
            <div class="meta">
              <h2 style="margin:0"><?= htmlspecialchars($empresaNombre) ?></h2>
              <small><?= htmlspecialchars($giro) ?></small>
            </div>
            <div>
              <?php if($estatus==='Activa'): ?>
                <span class="badge ok">Activa</span>
              <?php else: ?>
                <span class="badge warn"><?= htmlspecialchars($estatus) ?></span>
              <?php endif; ?>
            </div>
          </div>

          <div class="info-grid">
            <div class="row"><strong>RFC:</strong> <span><?= htmlspecialchars($rfc) ?></span></div>
            <div class="row"><strong>Sitio web:</strong> <a href="<?= htmlspecialchars($sitio) ?>" target="_blank"><?= htmlspecialchars($sitio) ?></a></div>
            <div class="row"><strong>Folio de convenio:</strong> <span><?= htmlspecialchars($folioConvenio) ?></span></div>
            <div class="row"><strong>Vigencia:</strong> <span><?= htmlspecialchars($vigencia) ?></span></div>
            <div class="row" style="grid-column:1/-1"><strong>Domicilio:</strong> <span><?= htmlspecialchars($domicilio) ?></span></div>
          </div>

          <div style="margin-top:12px;display:flex;gap:10px;flex-wrap:wrap">
            <a class="btn primary" href="perfil_solicitar_cambio.php">✏️ Solicitar actualización</a>
            <a class="btn" href="soporte.php">❓ Soporte</a>
          </div>
          <small class="muted" style="display:block;margin-top:8px;color:var(--muted)">
            Para cambios oficiales (razón social, RFC), adjunta documentación en la solicitud.
          </small>
        </div>
      </div>

      <div class="card">
        <header>Preferencias de notificación</header>
        <div class="content prefs">
          <?php foreach($prefs as $p): ?>
            <div class="pref">
              <label><?= htmlspecialchars($p['label']) ?></label>
              <input class="toggle" type="checkbox" <?= $p['value']?'checked':'' ?> disabled>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>

    <!-- Columna lateral -->
    <div class="col">
      <div class="card">
        <header>Contactos de la universidad</header>
        <div class="content contact-grid">
          <div class="contact">
            <h4>Responsable de Vinculación</h4>
            <p><?= htmlspecialchars($responsableVinc['nombre']) ?></p>
            <p><a href="mailto:<?= htmlspecialchars($responsableVinc['correo']) ?>"><?= htmlspecialchars($responsableVinc['correo']) ?></a> · <?= htmlspecialchars($responsableVinc['tel']) ?></p>
            <div class="chips">
              <span class="chip">Convenio</span>
              <span class="chip">Residencias</span>
            </div>
            <small class="muted" style="display:block;margin-top:6px;color:var(--muted)">Horario: <?= htmlspecialchars($responsableVinc['horario']) ?></small>
          </div>

          <div class="contact">
            <h4>Área Jurídica</h4>
            <p><?= htmlspecialchars($juridico['nombre']) ?></p>
            <p><a href="mailto:<?= htmlspecialchars($juridico['correo']) ?>"><?= htmlspecialchars($juridico['correo']) ?></a> · <?= htmlspecialchars($juridico['tel']) ?></p>
            <div class="chips">
              <span class="chip">Machote</span>
              <span class="chip">Anexos</span>
            </div>
            <small class="muted" style="display:block;margin-top:6px;color:var(--muted)">Horario: <?= htmlspecialchars($juridico['horario']) ?></small>
          </div>
        </div>
      </div>

      <div class="card">
        <header>Ayuda rápida</header>
        <div class="content">
          <ul class="help">
            <li>¿Actualizar datos? Usa <a href="perfil_solicitar_cambio.php">“Solicitar actualización”</a>.</li>
            <li>Problemas con documentos: revisa <a href="documentos_list.php">Documentos</a>.</li>
            <li>Vigencia próxima a vencer: ve a <a href="convenio_view.php#renovar">Renovación</a>.</li>
          </ul>
        </div>
      </div>

    </div>
  </section>

  <p class="foot">Portal de Empresa · Universidad · Área de Residencias</p>
</main>

</body>
</html>
