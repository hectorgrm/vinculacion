<?php
declare(strict_types=1);

// En el futuro se conectará con el handler y controlador:
require_once __DIR__ . '/../handler/empresa_perfil_handler.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal Empresa · Perfil</title>
  <link rel="stylesheet" href="../assets/css/perfilempresa/perfilempresa.css">
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
    <span class="company"><?= htmlspecialchars($empresaNombre ?? 'Empresa') ?></span>
    <a href="portal_list.php" class="btn">Inicio</a>
    <a href="../../common/logout.php" class="btn">Salir</a>
  </div>
</header>

<main class="container">

  <section class="titlebar">
    <div>
      <h1>Perfil de la empresa</h1>
      <p>Consulta tu información registrada y los contactos de la universidad.</p>
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
        <h2 style="margin:0"><?= htmlspecialchars($empresaNombre ?? '') ?></h2>
        <small><?= htmlspecialchars($sector ?? '') ?></small>
      </div>
      <div>
        <?php if (isset($estatus) && strtolower($estatus) === 'activa'): ?>
          <span class="badge ok">Activa</span>
        <?php elseif (isset($estatus) && strtolower($estatus) === 'en revisión'): ?>
          <span class="badge warn">En revisión</span>
        <?php else: ?>
          <span class="badge danger"><?= htmlspecialchars($estatus ?? 'Inactiva') ?></span>
        <?php endif; ?>
      </div>
    </div>

    <div class="info-grid">
      <div class="row"><strong>RFC:</strong> <span><?= htmlspecialchars($rfc ?? '') ?></span></div>
      <div class="row"><strong>Representante:</strong> <span><?= htmlspecialchars($representante ?? '') ?></span></div>
      <div class="row"><strong>Cargo:</strong> <span><?= htmlspecialchars($cargoRepresentante ?? '') ?></span></div>
      <div class="row"><strong>Teléfono:</strong> <span><?= htmlspecialchars($telefono ?? '') ?></span></div>
      <div class="row"><strong>Correo de contacto:</strong> <span><?= htmlspecialchars($contactoEmail ?? '') ?></span></div>
      <div class="row"><strong>Sitio web:</strong>
        <?php if (!empty($sitioWeb)): ?>
          <a href="<?= htmlspecialchars($sitioWeb) ?>" target="_blank"><?= htmlspecialchars($sitioWeb) ?></a>
        <?php else: ?>
          <span>—</span>
        <?php endif; ?>
      </div>
      <div class="row" style="grid-column:1/-1">
        <strong>Dirección:</strong>
        <span>
          <?= htmlspecialchars($direccion ?? '') ?>,
          <?= htmlspecialchars($municipio ?? '') ?>,
          <?= htmlspecialchars($estado ?? '') ?>,
          C.P. <?= htmlspecialchars($cp ?? '') ?>
        </span>
      </div>
    </div>

    <div style="margin-top:12px;display:flex;gap:10px;flex-wrap:wrap">
      <a class="btn primary" href="mailto:vinculacion@uni.mx">✏️ Solicitar actualización</a>
      <a class="btn" href="soporte.php">❓ Soporte</a>
    </div>
    <small class="muted" style="display:block;margin-top:8px;color:var(--muted)">
      Para cambios oficiales (razón social, RFC, representante legal), adjunta la documentación en la solicitud.
    </small>
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
            <p>Ing. Mariana López</p>
            <p><a href="mailto:vinculacion@uni.mx">vinculacion@uni.mx</a> · (33) 5555 0001</p>
            <div class="chips">
              <span class="chip">Convenio</span>
              <span class="chip">Residencias</span>
            </div>
            <small class="muted" style="display:block;margin-top:6px;color:var(--muted)">Horario: 9:00–15:00</small>
          </div>

          <div class="contact">
            <h4>Área Jurídica</h4>
            <p>Lic. Carlos Ruiz</p>
            <p><a href="mailto:juridico@uni.mx">juridico@uni.mx</a> · (33) 5555 0002</p>
            <div class="chips">
              <span class="chip">Machote</span>
              <span class="chip">Anexos</span>
            </div>
            <small class="muted" style="display:block;margin-top:6px;color:var(--muted)">Horario: 9:00–15:00</small>
          </div>
        </div>
      </div>
    </div>
  </section>

  <p class="foot">Portal de Empresa · Universidad · Área de Residencias</p>
</main>

</body>
</html>
