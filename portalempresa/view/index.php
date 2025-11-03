<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../model/PortalEmpresaEmpresaModel.php';

use PortalEmpresa\Model\PortalEmpresaEmpresaModel;

if (!isset($_SESSION['portal_empresa']) || !is_array($_SESSION['portal_empresa'])) {
    header('Location: login.php?error=session');
    exit;
}

$portalSession = $_SESSION['portal_empresa'];
$empresaId = (int) ($portalSession['empresa_id'] ?? 0);

$empresaModel = new PortalEmpresaEmpresaModel();
$empresaData = [];

if ($empresaId > 0) {
    try {
        $empresaRecord = $empresaModel->findEmpresaById($empresaId);
        if (is_array($empresaRecord)) {
            $empresaData = $empresaRecord;
        }
    } catch (\Throwable $exception) {
        $empresaData = [];
    }
}

$empresaNombre = (string) ($empresaData['nombre'] ?? ($portalSession['empresa_nombre'] ?? 'Empresa'));
$empresaNumeroControl = (string) ($empresaData['numero_control'] ?? ($portalSession['empresa_numero_control'] ?? ''));
$empresaEstatusRaw = (string) ($empresaData['estatus'] ?? ($portalSession['empresa_estatus'] ?? ''));
$empresaEstatus = strtolower($empresaEstatusRaw);

$empresaRfc = trim((string) ($empresaData['rfc'] ?? ''));
$empresaRepresentante = trim((string) ($empresaData['representante'] ?? ''));
$empresaCargoRepresentante = trim((string) ($empresaData['cargo_representante'] ?? ''));
$empresaSector = trim((string) ($empresaData['sector'] ?? ''));
$empresaCorreo = trim((string) ($empresaData['contacto_email'] ?? ''));
$empresaTelefono = trim((string) ($empresaData['telefono'] ?? ''));

$direccionPartes = array_filter([
    isset($empresaData['direccion']) ? trim((string) $empresaData['direccion']) : '',
    isset($empresaData['municipio']) ? trim((string) $empresaData['municipio']) : '',
    isset($empresaData['estado']) ? trim((string) $empresaData['estado']) : '',
    isset($empresaData['cp']) ? trim((string) $empresaData['cp']) : '',
], static function (string $value): bool {
    return $value !== '';
});

$empresaDireccion = $direccionPartes !== [] ? implode(', ', $direccionPartes) : '';

$estatusBadgeClass = 'warn';
switch ($empresaEstatus) {
    case 'activa':
        $estatusBadgeClass = 'ok';
        break;
    case 'suspendida':
    case 'inactiva':
        $estatusBadgeClass = 'danger';
        break;
    default:
        $estatusBadgeClass = 'warn';
        break;
}

$empresaNombreDisplay = $empresaNombre !== '' ? $empresaNombre : '—';
$empresaRepresentanteDisplay = $empresaRepresentante !== '' ? $empresaRepresentante : '—';
$empresaCargoDisplay = $empresaCargoRepresentante !== '' ? $empresaCargoRepresentante : '';
$empresaRfcDisplay = $empresaRfc !== '' ? $empresaRfc : '—';
$empresaSectorDisplay = $empresaSector !== '' ? $empresaSector : '—';
$empresaCorreoDisplay = $empresaCorreo !== '' ? $empresaCorreo : '—';
$empresaTelefonoDisplay = $empresaTelefono !== '' ? $empresaTelefono : '—';
$empresaDireccionDisplay = $empresaDireccion !== '' ? $empresaDireccion : '—';
$empresaEstatusDisplay = $empresaEstatusRaw !== '' ? $empresaEstatusRaw : 'En revisión';

$empresaNombreEsc = htmlspecialchars($empresaNombreDisplay, ENT_QUOTES, 'UTF-8');
$empresaRepresentanteEsc = htmlspecialchars($empresaRepresentanteDisplay, ENT_QUOTES, 'UTF-8');
$empresaCargoEsc = $empresaCargoDisplay !== '' ? htmlspecialchars($empresaCargoDisplay, ENT_QUOTES, 'UTF-8') : '';
$empresaRfcEsc = htmlspecialchars($empresaRfcDisplay, ENT_QUOTES, 'UTF-8');
$empresaSectorEsc = htmlspecialchars($empresaSectorDisplay, ENT_QUOTES, 'UTF-8');
$empresaCorreoEsc = htmlspecialchars($empresaCorreoDisplay, ENT_QUOTES, 'UTF-8');
$empresaTelefonoEsc = htmlspecialchars($empresaTelefonoDisplay, ENT_QUOTES, 'UTF-8');
$empresaDireccionEsc = htmlspecialchars($empresaDireccionDisplay, ENT_QUOTES, 'UTF-8');
$empresaEstatusEsc = htmlspecialchars($empresaEstatusDisplay, ENT_QUOTES, 'UTF-8');
$estatusBadgeClassEsc = htmlspecialchars($estatusBadgeClass, ENT_QUOTES, 'UTF-8');

$kpiActivos    = 0;
$kpiConcluidos = 0;
$kpiDocsOk     = 0;
$kpiDocsPend   = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal de Empresa · Inicio</title>

  <!-- Si ya tienes un CSS general del portal, úsalo aquí -->
  <link rel="stylesheet" href="../../assets/css/portal/portal_list.css">

  <!-- Fallback mínimo por si aún no creas el CSS -->
  <style>
    :root{--primary:#1f6feb;--ink:#0f172a;--muted:#64748b;--panel:#fff;--bg:#f6f8fb;--border:#e5e7eb;--ok:#16a34a;--warn:#f59e0b;--danger:#e11d48;--radius:16px;--shadow:0 6px 20px rgba(2,6,23,.06)}
    *{box-sizing:border-box} body{margin:0;font-family:Inter,system-ui,Segoe UI,Roboto,Arial,sans-serif;background:var(--bg);color:var(--ink)}
    .btn{display:inline-block;border:1px solid var(--border);background:#fff;padding:10px 14px;border-radius:12px;text-decoration:none;color:var(--ink);font-weight:600}
    .btn:hover{background:#f8fafc}.btn.primary{background:var(--primary);border-color:var(--primary);color:#fff}
    .badge{display:inline-block;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:700}
    .badge.ok{background:#dcfce7;color:#166534}.badge.warn{background:#fff7ed;color:#9a3412;border:1px solid #fed7aa}.badge.danger{background:#fee2e2;color:#991b1b;border:1px solid #fecaca}
    .portal-header{background:#fff;border-bottom:1px solid var(--border);padding:14px 18px;display:flex;justify-content:space-between;align-items:center}
    .brand{display:flex;gap:12px;align-items:center}.logo{width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,var(--primary),#6ea8ff)}
    .userbox{display:flex;gap:8px;align-items:center}.userbox .company{font-weight:700;color:#334155}
    .container{max-width:1200px;margin:20px auto;padding:0 14px}
    .welcome{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;margin-bottom:16px}
    .welcome h1{margin:0 0 6px 0;font-size:22px}.welcome p{margin:0;color:var(--muted)}
    .strip{background:#fff;border:1px solid var(--border);border-radius:14px;box-shadow:var(--shadow);padding:14px;display:grid;grid-template-columns:1.4fr 1fr;gap:14px}
    .strip .left p{margin:6px 0}
    .stats{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-top:8px}
    .kpi{background:#f8fafc;border:1px solid var(--border);border-radius:12px;padding:10px;text-align:center}
    .kpi .num{font-size:20px;font-weight:800}.kpi .lbl{font-size:12px;color:var(--muted)}
    .cards{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:18px}
    .card{background:var(--panel);border:1px solid var(--border);border-radius:16px;box-shadow:var(--shadow);padding:16px;display:flex;flex-direction:column;gap:10px}
    .card h3{margin:0;font-size:18px}.card p{margin:0;color:var(--muted)}
    .card .actions{margin-top:auto;display:flex;gap:8px;flex-wrap:wrap}
    .hint{color:#64748b;font-size:12px;margin-top:16px}
    @media (max-width: 1024px){.strip{grid-template-columns:1fr}.cards{grid-template-columns:1fr 1fr}}
    @media (max-width: 680px){.cards{grid-template-columns:1fr}.stats{grid-template-columns:1fr 1fr}}
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
      <h3>Información de la empresa</h3>

      <p><strong>Nombre:</strong> <?= $empresaNombreEsc ?></p>
      <p><strong>RFC:</strong> <?= $empresaRfcEsc ?></p>
      <p><strong>Representante:</strong>
        <?php if ($empresaRepresentanteDisplay === '—'): ?>
          <?= $empresaRepresentanteEsc ?>
        <?php else: ?>
          <?= $empresaRepresentanteEsc ?>
          <?php if ($empresaCargoDisplay !== ''): ?>
            · <em><?= $empresaCargoEsc ?></em>
          <?php endif; ?>
        <?php endif; ?>
      </p>
      <p><strong>Sector:</strong> <?= $empresaSectorEsc ?></p>
      <p><strong>Correo:</strong> <?= $empresaCorreoEsc ?></p>
      <p><strong>Teléfono:</strong> <?= $empresaTelefonoEsc ?></p>
      <p><strong>Dirección:</strong> <?= $empresaDireccionEsc ?></p>
      <p><strong>Estatus:</strong> <span class="badge <?= $estatusBadgeClassEsc ?>"><?= $empresaEstatusEsc ?></span></p>

      <div class="hint">
        Puedes actualizar tus datos de contacto o representante desde el apartado “Perfil de Empresa”.
      </div>
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
