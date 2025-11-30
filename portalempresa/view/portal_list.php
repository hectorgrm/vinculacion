<?php
// Ejemplos de datos (reemplaza con tu sesión/BD)
$empresaNombre   = 'Casa del Barrio';
$estadoConvenio  = 'Aprobado'; // 'Aprobado' | 'Por vencer' | 'Vencido'
$folioConvenio   = 'CV-2025-012';
$versionMachote  = 'Institucional v1.2';
$vigenciaInicio  = '2025-06-01';
$vigenciaFin     = '2026-05-30';

$kpiActivos      = 2;
$kpiConcluidos   = 5;
$kpiDocsOk       = 7;
$kpiDocsPend     = 1;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal de Empresa · Inicio</title>

  <link rel="stylesheet" href="../assets/css/modules/portal_list.css">
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
    <a href="../portalempresa/portal_list.php" class="btn">Inicio</a>
    <a href="../../common/logout.php" class="btn">Salir</a>
  </div>
</header>

<main class="container">

  <!-- Bienvenida + resumen de convenio -->
  <section class="welcome">
    <div>
      <h1>¡Hola, <?= htmlspecialchars($empresaNombre) ?>!</h1>
      <p>Desde aquí puedes consultar tu convenio, documentos, estudiantes y reportes.</p>
    </div>
  </section>

  <section class="strip">
    <div class="left">
      <h3>Estado del convenio</h3>
      <p><strong>Folio:</strong> <?= htmlspecialchars($folioConvenio) ?> · <strong>Versión:</strong> <?= htmlspecialchars($versionMachote) ?></p>
      <p><strong>Vigencia:</strong> <?= htmlspecialchars($vigenciaInicio) ?> — <?= htmlspecialchars($vigenciaFin) ?></p>
      <p>
        <strong>Estatus:</strong>
        <?php if ($estadoConvenio === 'Aprobado'): ?>
          <span class="badge ok">Aprobado</span>
        <?php elseif ($estadoConvenio === 'Por vencer'): ?>
          <span class="badge warn">Por vencer</span>
        <?php else: ?>
          <span class="badge danger">Vencido</span>
        <?php endif; ?>
      </p>
      <div class="hint">Si tu convenio está por vencer, puedes solicitar renovación desde la tarjeta “Convenio”.</div>
    </div>
    <div class="right">
      <div class="stats">
        <div class="kpi">
          <div class="num"><?= (int)$kpiActivos ?></div>
          <div class="lbl">Residentes activos</div>
        </div>
        <div class="kpi">
          <div class="num"><?= (int)$kpiConcluidos ?></div>
          <div class="lbl">Residentes concluidos</div>
        </div>
        <div class="kpi">
          <div class="num"><?= (int)$kpiDocsOk ?></div>
          <div class="lbl">Docs aprobados</div>
        </div>
        <div class="kpi">
          <div class="num"><?= (int)$kpiDocsPend ?></div>
          <div class="lbl">Docs pendientes</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Tarjetas de navegación -->
  <section class="cards">

    <!-- Documento final (machote aprobado) -->
    <article class="card">
      <h3>Documento final (acuerdo)</h3>
      <p>Consulta y descarga el documento aprobado por ambas partes.</p>
      <div class="actions">
        <a class="btn primary" href="machote_view_aprobado.php">📄 Ver documento</a>
        <a class="btn" href="machote_view_aprobado.php#descargar">⬇️ Descargar</a>
      </div>
    </article>

    <!-- Convenio -->
    <article class="card">
      <h3>Convenio</h3>
      <p>Datos del convenio vigente, anexos y renovación.</p>
      <div class="actions">
        <a class="btn primary" href="convenio_view.php">📑 Ver convenio</a>
      </div>
    </article>

    <!-- Documentos -->
    <article class="card">
      <h3>Documentos</h3>
      <p>Consulta el estado de los documentos solicitados por Residencias.</p>
      <div class="actions">
        <a class="btn primary" href="documentos_list.php">📂 Ver documentos</a>
        <a class="btn" href="documentos_list.php#subir">⬆️ Subir actualización</a>
      </div>
    </article>

    <!-- Estudiantes -->
    <article class="card">
      <h3>Estudiantes</h3>
      <p>Revisa residencias activas e histórico de estudiantes.</p>
      <div class="actions">
        <a class="btn primary" href="estudiantes_list.php">👨‍🎓 Ver estudiantes</a>
      </div>
    </article>

    <!-- Reportes -->
    <article class="card">
      <h3>Reportes</h3>
      <p>Indicadores básicos y exportaciones.</p>
      <div class="actions">
        <a class="btn primary" href="reportes_resumen.php">📊 Abrir reportes</a>
      </div>
    </article>

    <!-- Perfil & Contacto -->
    <article class="card">
      <h3>Perfil de la empresa</h3>
      <p>Datos de contacto y responsables con la universidad.</p>
      <div class="actions">
        <a class="btn primary" href="perfil_empresa.php">🏢 Ver perfil</a>
        <a class="btn" href="soporte.php">❓ Soporte / Ayuda</a>
      </div>
    </article>

  </section>

  <p class="hint">Si tienes dudas o necesitas asistencia, visita la sección <a href="soporte.php">Soporte</a>.</p>

</main>

</body>
</html>
