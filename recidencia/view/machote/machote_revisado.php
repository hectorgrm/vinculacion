<?php
// ===================== Ejemplo de datos (REMPLAZA CON TU CONTROLADOR/BD) =====================
$empresaId       = 45;
$empresaNombre   = 'Casa del Barrio';
$revisionId      = 123;
$docVersion      = 'Institucional v1.2';

// Estado de la revisión y aprobaciones
$hilosAbiertos   = 0;     // si > 0, no se puede generar
$hilosResueltos  = 4;
$aprobAdmin      = true;  // switch admin
$aprobEmpresa    = true;  // switch empresa
$estadoRevision  = 'aprobado'; // en_revision | con_observaciones | aprobado

// Convenio generado ya?
$convenioId      = null;  // si ya existe, colocar el ID del convenio
$convenioGenerado = !is_null($convenioId);

// Rutas de archivos finales (si existen)
$pdfMachoteFinal = '../../uploads/machote_v12_final.pdf';  // deja null si aún no existe
$pdfConvenioFirm = '../../uploads/convenio_v12_firmado.pdf'; // deja null si aún no existe

// Lógica UI
$puedeGenerar = ($hilosAbiertos === 0 && $aprobAdmin && $aprobEmpresa && !$convenioGenerado);

// Toast por query param (p.ej., ?ok=convenio)
$toast = null;
if (isset($_GET['ok']) && $_GET['ok'] === 'convenio') {
  $toast = '¡Convenio generado correctamente!';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>📝 Machote Revisado · Residencias</title>

  <!-- Reutiliza tus estilos globales -->
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <link rel="stylesheet" href="../../assets/css/modules/machote.css" />

  <style>
    /* Ajustes locales */
    .topbar.success .badge.ok{ background:#d1fae5; color:#065f46; }
    .kpi-section{ display:grid; grid-template-columns:repeat(4,1fr); gap:12px }
    .kpi-section .kpi{ background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:12px }
    .kpi-section .kpi h4{ margin:0 0 6px 0; font-size:14px; color:#475569 }
    .kpi-section .kpi .kpi-num{ font-weight:800; font-size:20px }
    .kpi-section .kpi.wide{ grid-column:span 2 }
    .progress{ width:100%; height:10px; background:#eef2f7; border-radius:999px; overflow:hidden }
    .progress .bar{ height:10px; background:#16a34a; width:100% }
    .approvals{ display:flex; gap:12px; align-items:center; justify-content:flex-end; flex-wrap:wrap }
    .switch{ display:flex; align-items:center; gap:10px; opacity:.8 }
    .switch input{ pointer-events:none }
    .badge.ok{ background:#d1fae5; color:#065f46; padding:4px 10px; border-radius:999px; font-weight:700; font-size:12px }
    .file-list{ list-style:none; margin:0; padding:0 }
    .file-list li{ margin:6px 0 }
    .hint{ color:#64748b; margin-top:8px }
    .toast{
      position:fixed; right:16px; bottom:16px; background:#111827; color:#fff;
      padding:10px 14px; border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,.2); z-index:50; opacity:.98
    }
    .actions .btn[disabled]{ opacity:.5; cursor:not-allowed }
    @media (max-width: 960px){
      .kpi-section{ grid-template-columns:1fr 1fr }
      .kpi-section .kpi.wide{ grid-column:span 2 }
    }
  </style>
</head>
<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <!-- 🧭 Encabezado -->
      <header class="topbar success">
        <div>
          <h2>📝 Machote revisado</h2>
          <p class="subtitle">
            Empresa: <strong><?= htmlspecialchars($empresaNombre) ?></strong> · Revisión <strong>#<?= (int)$revisionId ?></strong> ·
            Machote: <strong><?= htmlspecialchars($docVersion) ?></strong> ·
            Estado:
            <?php if ($estadoRevision === 'aprobado'): ?>
              <span class="badge ok">Aprobado</span>
            <?php elseif ($estadoRevision === 'con_observaciones'): ?>
              <span class="badge warn">Con observaciones</span>
            <?php else: ?>
              <span class="badge">En revisión</span>
            <?php endif; ?>
          </p>
        </div>
        <div class="actions" style="display:flex; gap:10px; flex-wrap:wrap;">
          <?php if ($convenioGenerado): ?>
            <a class="btn primary" href="../convenio/convenio_view.php?id=<?= (int)$convenioId ?>">📑 Ver convenio</a>
          <?php else: ?>
            <form action="revision_generate_convenio_action.php" method="post" style="display:inline;">
              <input type="hidden" name="revision_id" value="<?= (int)$revisionId ?>">
              <button class="btn primary"
                      <?= $puedeGenerar ? '' : 'disabled' ?>
                      title="Se habilita con 0 hilos abiertos y ambas aprobaciones activas">
                📄 Generar convenio
              </button>
            </form>
          <?php endif; ?>
          <a href="../empresa/empresa_view.php?id=<?= (int)$empresaId ?>" class="btn secondary">⬅ Volver</a>
        </div>
      </header>

      <!-- 📊 KPIs + Aprobaciones congeladas -->
      <section class="card kpi-section">
        <div class="kpi">
          <h4>Hilos abiertos</h4>
          <div class="kpi-num"><?= (int)$hilosAbiertos ?></div>
        </div>
        <div class="kpi">
          <h4>Hilos resueltos</h4>
          <div class="kpi-num"><?= (int)$hilosResueltos ?></div>
        </div>
        <div class="kpi wide">
          <h4>Progreso</h4>
          <div class="progress"><div class="bar" style="width: <?= $hilosAbiertos === 0 ? '100' : round(($hilosResueltos / max(1,$hilosResueltos + $hilosAbiertos))*100) ?>%"></div></div>
          <small><?= $hilosAbiertos === 0 ? '100% resuelto' : (int)round(($hilosResueltos / max(1,$hilosResueltos + $hilosAbiertos))*100) . '% completado' ?></small>
        </div>
        <div class="approvals">
          <label class="switch" title="Aprobación Admin (bloqueada al estar cerrado)">
            <input type="checkbox" <?= $aprobAdmin ? 'checked' : '' ?> disabled>
            <span class="label">Aprobado Admin</span>
          </label>
          <label class="switch" title="Aprobación Empresa (bloqueada al estar cerrado)">
            <input type="checkbox" <?= $aprobEmpresa ? 'checked' : '' ?> disabled>
            <span class="label">Aprobado Empresa</span>
          </label>
        </div>
      </section>

      <!-- ✅ Resumen final de comentarios -->
      <section class="card">
        <header>🧾 Resumen final de comentarios</header>
        <div class="content threads">
          <!-- EJEMPLOS. En producción, itera sobre tus hilos resueltos -->
          <article class="thread">
            <div class="meta">
              <span class="badge ok">Resuelto</span>
              <span class="author empresa">Empresa</span>
              <span class="time">hace 3 días</span>
            </div>
            <h4>Cláusula de vigencia</h4>
            <p>Se modificó a 18 meses conforme a la propuesta de la empresa.</p>
          </article>

          <article class="thread">
            <div class="meta">
              <span class="badge ok">Resuelto</span>
              <span class="author admin">Admin</span>
              <span class="time">hace 2 días</span>
            </div>
            <h4>Confidencialidad</h4>
            <p>Se anexó cláusula adicional de confidencialidad estándar.</p>
          </article>

          <article class="thread">
            <div class="meta">
              <span class="badge ok">Resuelto</span>
              <span class="author empresa">Empresa</span>
              <span class="time">hace 1 día</span>
            </div>
            <h4>Propiedad Intelectual</h4>
            <p>Se acordó que los entregables estarán bajo licencia interna compartida.</p>
          </article>

          <article class="thread">
            <div class="meta">
              <span class="badge ok">Resuelto</span>
              <span class="author admin">Admin</span>
              <span class="time">hace 1 día</span>
            </div>
            <h4>Firmas</h4>
            <p>Se confirmó que ambas partes firmarán versión digital.</p>
          </article>
        </div>
      </section>

      <!-- 📎 Archivos finales -->
      <section class="card">
        <header>📎 Archivos finales</header>
        <div class="content">
          <?php if ($pdfMachoteFinal || $pdfConvenioFirm): ?>
            <ul class="file-list">
              <?php if ($pdfMachoteFinal): ?>
                <li>
                  <a href="<?= htmlspecialchars($pdfMachoteFinal) . '?rev=' . (int)$revisionId ?>" target="_blank" rel="noopener">
                    📄 Machote <?= htmlspecialchars($docVersion) ?> (Final)
                  </a>
                </li>
              <?php else: ?>
                <li class="hint">Machote final aún no publicado.</li>
              <?php endif; ?>

              <?php if ($pdfConvenioFirm): ?>
                <li>
                  <a href="<?= htmlspecialchars($pdfConvenioFirm) . '?rev=' . (int)$revisionId ?>" target="_blank" rel="noopener">
                    📑 Convenio firmado (PDF)
                  </a>
                </li>
              <?php else: ?>
                <li class="hint">Convenio firmado pendiente.</li>
              <?php endif; ?>
            </ul>
          <?php else: ?>
            <p class="hint">Aún no hay archivos finales disponibles.</p>
          <?php endif; ?>
        </div>
      </section>

      <footer class="hint">
        <small>
          El botón “Generar convenio” se habilita con 0 hilos abiertos y ambas aprobaciones activas.
          Tras generarlo, serás redirigido al detalle del convenio.
        </small>
      </footer>
    </main>
  </div>

  <?php if ($toast): ?>
    <div class="toast" role="status" aria-live="polite"><?= htmlspecialchars($toast) ?></div>
    <script>
      setTimeout(()=>{ const t=document.querySelector('.toast'); if(t){ t.remove(); } }, 3500);
    </script>
  <?php endif; ?>
</body>
</html>
