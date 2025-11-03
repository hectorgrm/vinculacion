<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal Empresa · Solicitar actualización de perfil</title>

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
    .btn:hover{background:#f8fafc}
    .btn.primary{background:var(--primary);border-color:var(--primary);color:#fff}
    .btn.primary:hover{background:var(--primary-600)}
    .btn.small{padding:8px 10px;font-size:13px}
    .btn.ghost{background:transparent}

    /* Layout */
    .container{max-width:1100px;margin:20px auto;padding:0 14px}
    .titlebar{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:14px}
    .titlebar h1{margin:0 0 4px 0;font-size:22px}
    .titlebar p{margin:0;color:var(--muted)}

    .card{background:var(--panel);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow)}
    .card header{padding:12px 14px;border-bottom:1px solid var(--border);font-weight:700}
    .card .content{padding:14px}

    .alert{border:1px solid var(--border);border-radius:12px;padding:12px;margin-bottom:12px;display:flex;gap:10px;align-items:flex-start}
    .alert.ok{background:#ecfdf5;border-color:#bbf7d0;color:#14532d}
    .alert.warn{background:#fff7ed;border-color:#fed7aa;color:#9a3412}
    .alert.info{background:#eff6ff;border-color:#bfdbfe;color:#1e40af}

    /* Form */
    form{display:grid;grid-template-columns:1fr 1fr;gap:14px}
    .full{grid-column:1/-1}
    label{font-weight:600;color:#334155;margin-bottom:6px;display:block}
    input[type="text"], input[type="email"], input[type="tel"], input[type="url"], textarea, select{
      width:100%;padding:10px;border:1px solid var(--border);border-radius:10px;background:#fff
    }
    textarea{resize:vertical}
    .help{font-size:12px;color:var(--muted);margin-top:6px}
    .row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
    @media (max-width: 760px){
      form{grid-template-columns:1fr}
      .row{grid-template-columns:1fr}
    }

    .files{border:1px dashed #cbd5e1;border-radius:12px;background:#f8fafc;padding:12px}
    .chips{display:flex;gap:8px;flex-wrap:wrap}
    .chip{border:1px solid var(--border);border-radius:999px;padding:4px 10px;font-size:12px;background:#fff}

    .actions{display:flex;gap:10px;flex-wrap:wrap;justify-content:flex-end;margin-top:6px}
    .muted{color:var(--muted)}
    .foot{margin-top:16px;color:var(--muted);font-size:12px;text-align:center}
  </style>
</head>
<body>

<?php
// Ejemplo: simular nombre de empresa desde sesión
$empresaNombre = 'Casa del Barrio';

// Si tras procesar POST quieres mostrar éxito, podrías usar ?ok=1
$ok = isset($_GET['ok']) && $_GET['ok'] == '1';
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
    <a class="btn" href="portal_list.php">Inicio</a>
    <a class="btn" href="perfil_empresa.php">Perfil</a>
    <a class="btn" href="../../common/logout.php">Salir</a>
  </div>
</header>

<main class="container">

  <section class="titlebar">
    <div>
      <h1>Solicitar actualización de datos</h1>
      <p>Envía cambios de razón social, RFC, domicilio, contactos u otra información de tu empresa.</p>
    </div>
    <div class="actions">
      <a href="perfil_empresa.php" class="btn">⬅ Volver al perfil</a>
    </div>
  </section>

  <?php if($ok): ?>
    <div class="alert ok">
      <div>✅ <strong>Solicitud enviada.</strong> Recibirás confirmación por correo cuando sea revisada.</div>
    </div>
  <?php else: ?>
    <div class="alert info">
      <div>ℹ️ <strong>Importante:</strong> Para cambios oficiales (Razón Social, RFC), adjunta documentación probatoria.</div>
    </div>
  <?php endif; ?>

  <section class="card">
    <header>Formulario de solicitud</header>
    <div class="content">
      <form action="perfil_solicitar_cambio_action.php" method="post" enctype="multipart/form-data">
        <!-- Datos generales -->
        <div class="full"><h3 style="margin:0 0 6px 0">Datos generales</h3></div>

        <div>
          <label for="razon">Razón social</label>
          <input type="text" id="razon" name="razon" placeholder="Ej. Casa del Barrio A.C.">
          <div class="help">Déjalo vacío si no deseas cambiarlo.</div>
        </div>

        <div>
          <label for="rfc">RFC</label>
          <input type="text" id="rfc" name="rfc" placeholder="Ej. CDB810101AA1" maxlength="20">
        </div>

        <div class="full">
          <label for="domicilio">Domicilio fiscal</label>
          <input type="text" id="domicilio" name="domicilio" placeholder="Calle, número, colonia, ciudad, estado, C.P.">
        </div>

        <div>
          <label for="sitio">Sitio web</label>
          <input type="url" id="sitio" name="sitio" placeholder="https://tusitio.com">
        </div>

        <div>
          <label for="telefono">Teléfono principal</label>
          <input type="tel" id="telefono" name="telefono" placeholder="(33) 1234 5678">
        </div>

        <!-- Contacto responsable -->
        <div class="full"><h3 style="margin:8px 0 6px 0">Contacto responsable</h3></div>

        <div>
          <label for="contacto_nombre">Nombre</label>
          <input type="text" id="contacto_nombre" name="contacto_nombre" placeholder="Nombre y apellido">
        </div>

        <div>
          <label for="contacto_email">Correo</label>
          <input type="email" id="contacto_email" name="contacto_email" placeholder="correo@empresa.com">
        </div>

        <div>
          <label for="contacto_puesto">Puesto</label>
          <input type="text" id="contacto_puesto" name="contacto_puesto" placeholder="Ej. Responsable de RR.HH.">
        </div>

        <div>
          <label for="contacto_tel">Teléfono directo</label>
          <input type="tel" id="contacto_tel" name="contacto_tel" placeholder="(33) 5555 0000">
        </div>

        <!-- Personas autorizadas -->
        <div class="full"><h3 style="margin:8px 0 6px 0">Personas autorizadas</h3></div>
        <div class="full">
          <textarea id="autorizados" name="autorizados" rows="3" placeholder="Nombre completo, puesto y correo de las personas autorizadas para trámites de residencias."></textarea>
          <div class="help">Puedes listar varias personas separadas por líneas.</div>
        </div>

        <!-- Tipo de cambio -->
        <div class="full"><h3 style="margin:8px 0 6px 0">Tipo de cambio solicitado</h3></div>
        <div class="row">
          <div>
            <label for="tipo_cambio">Selecciona uno o más</label>
            <select id="tipo_cambio" name="tipo_cambio[]" multiple size="5">
              <option value="razon_rfc">Razón social / RFC</option>
              <option value="domicilio">Domicilio</option>
              <option value="contacto">Contacto responsable</option>
              <option value="autorizados">Personas autorizadas</option>
              <option value="web_tel">Sitio web / Teléfono</option>
              <option value="otros">Otros</option>
            </select>
            <div class="help">Mantén presionado Ctrl (o Cmd) para seleccionar múltiples opciones.</div>
          </div>
          <div>
            <label for="fecha_preferente">Fecha preferente</label>
            <input type="text" id="fecha_preferente" name="fecha_preferente" placeholder="Ej. antes del 30/11/2025">
            <div class="help">Opcional. Indica si hay urgencia o una fecha límite.</div>
          </div>
        </div>

        <!-- Explicación -->
        <div class="full">
          <label for="mensaje">Explicación / Justificación</label>
          <textarea id="mensaje" name="mensaje" rows="5" required placeholder="Describe claramente los cambios solicitados y el motivo."></textarea>
        </div>

        <!-- Adjuntos -->
        <div class="full">
          <label>Adjuntar documentación (opcional)</label>
          <div class="files">
            <input type="file" name="adjuntos[]" id="adjuntos" multiple accept=".pdf,.jpg,.jpeg,.png">
            <div class="help">Formatos: PDF, JPG o PNG. Tamaño recomendado: máx. 10 MB por archivo.</div>
          </div>
        </div>

        <!-- Consentimiento -->
        <div class="full">
          <label><input type="checkbox" name="consent" required> Confirmo que la información proporcionada es verídica y autorizo su uso para fines administrativos.</label>
        </div>

        <!-- Acciones -->
        <div class="full actions">
          <a href="perfil_empresa.php" class="btn">Cancelar</a>
          <button type="submit" class="btn primary">📨 Enviar solicitud</button>
        </div>

        <input type="hidden" name="empresa_id" value="45"><!-- Sustituir con el ID real de sesión -->
      </form>
    </div>
  </section>

  <p class="foot">Portal de Empresa · Universidad · Área de Residencias</p>
</main>

</body>
</html>
