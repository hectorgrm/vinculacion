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

    /* Contacts */
    .contact-grid{display:grid;grid-template-columns:1fr;gap:10px}
    .contact{border:1px solid var(--border);border-radius:12px;padding:12px;background:#fff}
    .contact h4{margin:0 0 6px 0}
    .contact p{margin:0;color:var(--muted)}

    /* Footer */
    .foot{margin-top:16px;color:var(--muted);font-size:12px;text-align:center}
  </style>
</head>
<body>

  <!-- Encabezado -->
  <header class="portal-header">
    <div class="brand">
      <div class="logo"></div>
      <div>
        <strong>Portal de Empresa</strong><br>
        <small>Residencias Profesionales</small>
      </div>
    </div>
    <div class="userbox">
      <span class="company">Nombre de la Empresa</span>
      <a href="#" class="btn">Inicio</a>
      <a href="#" class="btn">Salir</a>
    </div>
  </header>

  <!-- Contenedor principal -->
  <main class="container">

    <!-- Título -->
    <section class="titlebar">
      <div>
        <h1>Convenio con la Universidad</h1>
        <p>Consulta tu convenio vigente, descarga el PDF y revisa tus datos principales.</p>
      </div>
      <div class="actions">
        <a href="#" class="btn">📄 Documento final</a>
      </div>
    </section>

    <section class="grid">
      <!-- Columna izquierda -->
      <div class="col">
        <!-- Datos del convenio -->
        <div class="card">
          <header>Datos del convenio</header>
          <div class="content">
            <div class="summary">
              <div class="row"><strong>Folio:</strong> <span>CV-2025-012</span></div>
              <div class="row"><strong>Responsable académico:</strong> <span>Ing. Mariana López</span></div>
              <div class="row"><strong>Inicio:</strong> <span>2025-06-01</span></div>
              <div class="row"><strong>Fin:</strong> <span>2026-05-30</span></div>
              <div class="row">
                <strong>Estatus:</strong>
                <span><span class="badge ok">Activa</span></span>
              </div>
            </div>

            <!-- Estado visual según estatus -->
            <div class="strip activa">
              <strong>✅ Convenio activo.</strong>
              <span>Todo en orden.</span>
            </div>

            <!-- Ejemplos alternativos:
            <div class="strip revision">
              <strong>🕒 En revisión.</strong>
              <span>El convenio está siendo evaluado por Vinculación.</span>
            </div>

            <div class="strip suspendida">
              <strong>⛔ Suspendido.</strong>
              <span>Contacta al área de Vinculación para regularizar.</span>
            </div>
            -->
          </div>
        </div>


        <!-- PDF del convenio -->
        <div class="card" id="pdf">
          <header>Convenio (PDF)</header>
          <div class="content">
            <div class="pdf-frame">
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

<!-- Contactos de Residencias -->
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


    <p class="foot">Portal de Empresa · Universidad · Área de Residencias</p>
  </main>

</body>
</html>
