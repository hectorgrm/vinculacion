<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../common/functions/portal_session_guard.php';

$portalSession = portalEmpresaRequireSession('login.php');
$empresaNombre = trim((string) ($portalSession['empresa_nombre'] ?? ''));

if ($empresaNombre === '') {
    $empresaNombre = 'Empresa';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal de Empresa · Inicio</title>

  <!-- Si ya tienes un CSS general del portal, úsalo aquí -->
  <link rel="stylesheet" href="../assets/css/portal/index.css">
  <!-- Fallback mínimo por si aún no creas el CSS -->

</head>
<body>
<?php include __DIR__ . '/../layout/portal_empresa_header.php'; ?>

<main class="container"></main>



  <!-- Bienvenida + resumen de convenio -->
  <section class="welcome">
    <div>
      <h1>¡Hola, <?= htmlspecialchars($empresaNombre) ?>!</h1>
      <p>Desde aquí puedes consultar tu convenio, documentos, estudiantes y reportes.</p>
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
        <a class="btn" href="convenio_view.php#renovar">↺ Solicitar renovación</a>
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
