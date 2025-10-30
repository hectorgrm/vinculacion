<?php
declare(strict_types=1);

/** @var array{
 *     empresaId: ?int,
 *     empresa: ?array<string, mixed>,
 *     conveniosActivos: array<int, array<string, mixed>>,
 *     controllerError: ?string,
 *     notFoundMessage: ?string,
 *     inputError: ?string
 * } $handlerResult
 */
$handlerResult = require __DIR__ . '/../../handler/empresa/empresa_view_handler.php';

$empresaId = $handlerResult['empresaId'];
$empresa = $handlerResult['empresa'];
$controllerError = $handlerResult['controllerError'];
$notFoundMessage = $handlerResult['notFoundMessage'];
$inputError = $handlerResult['inputError'];
$conveniosActivos = $handlerResult['conveniosActivos'] ?? [];

if (!is_array($conveniosActivos)) {
    $conveniosActivos = [];
}

$nombre = 'Sin datos';
$rfc = 'N/A';
$representante = 'No especificado';
$telefono = 'No registrado';
$correo = 'No registrado';
$estatusClass = 'badge secondary';
$estatusLabel = 'Sin estatus';
$creadoEn = 'N/A';
$actualizadoEn = 'Sin actualizar';

if (is_array($empresa)) {
    $nombre = (string) ($empresa['nombre_label'] ?? ($empresa['nombre'] ?? $nombre));
    $rfc = (string) ($empresa['rfc_label'] ?? $rfc);
    $representante = (string) ($empresa['representante_label'] ?? $representante);
    $telefono = (string) ($empresa['telefono_label'] ?? $telefono);
    $correo = (string) ($empresa['correo_label'] ?? $correo);
    $estatusClass = (string) ($empresa['estatus_badge_class'] ?? $estatusClass);
    $estatusLabel = (string) ($empresa['estatus_badge_label'] ?? $estatusLabel);
    $creadoEn = (string) ($empresa['creado_en_label'] ?? $creadoEn);
    $actualizadoEn = (string) ($empresa['actualizado_en_label'] ?? $actualizadoEn);
}

$empresaIdQuery = $empresaId !== null ? (string) $empresaId : '';
$nuevoConvenioUrl = '../convenio/convenio_add.php';

if ($empresaIdQuery !== '') {
    $nuevoConvenioUrl .= '?empresa=' . urlencode($empresaIdQuery);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detalle de Empresa · Residencias Profesionales</title>

  <!-- Estilos -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css">
  <!-- Si ya tienes estilos específicos para esta vista, mantenlos: -->
  <link rel="stylesheet" href="../../assets/css/empresas/empresaview.css">
</head>

<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <div>
          <h2>🏢 Detalle de Empresa · Residencias Profesionales</h2>
          <p>Consulta y gestiona la información general, convenios y documentación de la empresa.</p>
        </div>
        <div class="actions">
          <!-- Progreso (misma carpeta) -->
          <a href="empresa_progreso.php?id_empresa=45" class="btn primary">📊 Ver Progreso</a>
          <!-- Volver al listado -->
          <a href="empresa_list.php" class="btn secondary">⬅ Volver</a>
        </div>
      </header>

      <!-- 🏢 Información General -->
      <section class="card">
        <header>🏢 Información General de la Empresa</header>
        <div class="content empresa-info">
          <?php if ($controllerError !== null || $inputError !== null || $notFoundMessage !== null) : ?>
            <div class="alert error" role="alert">
              <?php
              $message = $controllerError ?? $inputError ?? $notFoundMessage ?? 'No se pudo cargar la información de la empresa.';
              echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
              ?>
            </div>
          <?php else : ?>
            <div class="info-grid">
              <div><strong>Nombre:</strong> <?php echo htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?></div>
              <div><strong>RFC:</strong> <?php echo htmlspecialchars($rfc, ENT_QUOTES, 'UTF-8'); ?></div>
              <div><strong>Representante Legal:</strong> <?php echo htmlspecialchars($representante, ENT_QUOTES, 'UTF-8'); ?></div>
              <div><strong>Teléfono:</strong> <?php echo htmlspecialchars($telefono, ENT_QUOTES, 'UTF-8'); ?></div>
              <div><strong>Correo:</strong> <?php echo htmlspecialchars($correo, ENT_QUOTES, 'UTF-8'); ?></div>
              <div><strong>Estatus:</strong> <span class="<?php echo htmlspecialchars($estatusClass, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($estatusLabel, ENT_QUOTES, 'UTF-8'); ?></span></div>
              <div><strong>Fecha de Registro:</strong> <?php echo htmlspecialchars($creadoEn, ENT_QUOTES, 'UTF-8'); ?></div>
              <div><strong>Última actualización:</strong> <?php echo htmlspecialchars($actualizadoEn, ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
          <?php endif; ?>
        </div>
      </section>

      <!-- 📜 Convenios asociados -->
      <section class="card">
        <header>📜 Convenios Activos</header>
        <div class="content">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Versión</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Estatus</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($controllerError !== null || $inputError !== null || $notFoundMessage !== null): ?>
                <tr>
                  <td colspan="6" style="text-align:center;">No hay datos de convenios disponibles.</td>
                </tr>
              <?php elseif ($conveniosActivos === []): ?>
                <tr>
                  <td colspan="6" style="text-align:center;">No existen convenios activos registrados para esta empresa.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($conveniosActivos as $convenio): ?>
                  <?php
                  $convenioIdLabel = (string) ($convenio['id_label'] ?? '#');
                  $convenioVersion = (string) ($convenio['version_label'] ?? 'Sin version');
                  $convenioInicio = (string) ($convenio['fecha_inicio_label'] ?? 'N/A');
                  $convenioFin = (string) ($convenio['fecha_fin_label'] ?? 'N/A');
                  $convenioEstatusClass = (string) ($convenio['estatus_badge_class'] ?? 'badge secondary');
                  $convenioEstatusLabel = (string) ($convenio['estatus_badge_label'] ?? 'Sin estatus');
                  $convenioViewUrl = isset($convenio['view_url']) ? (string) $convenio['view_url'] : null;
                  $convenioEditUrl = isset($convenio['edit_url']) ? (string) $convenio['edit_url'] : null;
                  ?>
                  <tr>
                    <td><?php echo htmlspecialchars($convenioIdLabel, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($convenioVersion, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($convenioInicio, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($convenioFin, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                      <span class="<?php echo htmlspecialchars($convenioEstatusClass, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($convenioEstatusLabel, ENT_QUOTES, 'UTF-8'); ?>
                      </span>
                    </td>
                    <td>
                      <?php if ($convenioViewUrl !== null): ?>
                        <a href="<?php echo htmlspecialchars($convenioViewUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn small">👁️ Ver</a>
                      <?php endif; ?>
                      <?php if ($convenioEditUrl !== null): ?>
                        <a href="<?php echo htmlspecialchars($convenioEditUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn small">✏️ Editar</a>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>

          <div class="actions">
            <a href="<?php echo htmlspecialchars($nuevoConvenioUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn primary">➕ Nuevo Convenio</a>
          </div>
        </div>
      </section>

      <!-- 💬 Observaciones de Machote -->
      <!-- 💬 Revisión de Machote -->

      <!-- 🟢 Caso 1: Machote aprobado -->

      <section class="card">
        <header>📝 Revisión de Machote</header>
        <div class="content">
          <div class="review-summary">
            <strong>Versión aprobada:</strong> Institucional v1.2<br>
            <strong>Estado:</strong> <span class="badge ok">Aprobado</span><br>
            <ul class="file-list" style="margin-top:8px;">
              <li><a href="../../uploads/machote_v12_final.pdf" target="_blank">📄 Machote final (PDF)</a></li>
              <li><a href="../convenio/convenio_view.php?id=12">📑 Ver convenio generado</a></li>
            </ul>
          </div>
          <div class="actions">
            <a href="../machote/machote_revisado.php?id_empresa=45" class="btn secondary">👁️ Vista final</a>
          </div>
        </div>
      </section>
      <!-- 🟡 Caso 2: En revisión -->


      <section class="card">
        <header>📝 Revisión de Machote</header>
        <div class="content">
          <div class="review-summary">
            <strong>Versión activa:</strong> Institucional v1.2<br>
            <strong>Estado:</strong> <span class="badge en_revision">En revisión</span><br>
            <strong>Hilos abiertos:</strong> 1 · <strong>Resueltos:</strong> 3 · <strong>Progreso:</strong> 75%
          </div>
          <div class="actions">
            <a href="../machote/revisar.php?id_empresa=45" class="btn primary">💬 Abrir Revisión</a>
          </div>
        </div>
      </section>

      <!-- 🔔 Alertas -->
      <section class="card warn">
        <header>⚠ Alertas recientes</header>
        <div class="content">
          <ul class="alerts">
            <li>⚠ Convenio #15 está próximo a vencer (30 días restantes).</li>
            <li>⚠ Documento “Acta Constitutiva” sigue pendiente de aprobación.</li>
          </ul>
        </div>
      </section>

      <!-- 📂 Documentación Legal -->
      <section class="card">
        <header>
          📂 Documentación Legal
          <span class="subtitle">Control de documentos requeridos por Vinculación</span>
        </header>

        <div class="content">
          <?php
          // --- Datos simulados de ejemplo (puedes reemplazar luego con consulta real) ---
          $docsTotal = 5;      // Total de documentos requeridos
          $docsSubidos = 3;    // Archivos cargados por la empresa
          $docsAprobados = 2;  // Documentos validados por Vinculación
          $progreso = round(($docsSubidos / $docsTotal) * 100);
          ?>

          <!-- 🔢 Resumen visual -->
          <div class="docs-summary" style="margin-bottom:15px; display:flex; align-items:center; gap:20px; flex-wrap:wrap;">
            <div style="flex:1;">
              <strong>📄 Documentos requeridos:</strong> <?php echo $docsTotal; ?><br>
              <strong>📤 Subidos:</strong> <?php echo $docsSubidos; ?>  
              <strong>✅ Aprobados:</strong> <?php echo $docsAprobados; ?>
            </div>
            <div style="flex:1;">
              <label style="font-weight:600;">Progreso general:</label>
              <div style="background:#eee; border-radius:8px; overflow:hidden; height:10px; margin-top:4px;">
                <div style="width:<?php echo $progreso; ?>%; height:10px; background:#4caf50;"></div>
              </div>
              <small><?php echo $progreso; ?>% completado</small>
            </div>
          </div>

          <!-- 🧾 Tabla de resumen de documentos -->
          <table>
            <thead>
              <tr>
                <th>Documento</th>
                <th>Estado</th>
                <th>Última actualización</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <!-- ✅ Ejemplo: documento aprobado -->
              <tr>
                <td>Constancia SAT</td>
                <td><span class="badge ok">Aprobado</span></td>
                <td>2025-09-10</td>
                <td><a href="../../uploads/empresa_45/sat_constancia.pdf" class="btn small">📄 Ver</a></td>
              </tr>

              <!-- ⏳ Ejemplo: documento pendiente -->
              <tr>
                <td>Acta Constitutiva</td>
                <td><span class="badge pendiente">Pendiente</span></td>
                <td>—</td>
                <td>
                  <a href="../empresadocumentotipo/empresa_documentotipo_list.php?id_empresa=45" class="btn small primary">📁 Ver / Subir</a>
                </td>
              </tr>

              <!-- ⬆ Ejemplo: logotipo pendiente -->
              <tr>
                <td>Logotipo del Negocio</td>
                <td><span class="badge warn">Faltante</span></td>
                <td>—</td>
                <td>
                  <a href="../empresadocumentotipo/empresa_documentotipo_list.php?id_empresa=45" class="btn small primary">📁 Ver / Subir</a>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- 🔗 Acción principal -->
          <div class="actions" style="margin-top:16px; justify-content:flex-end;">
            <a href="../empresadocumentotipo/empresa_documentotipo_list.php?id_empresa=45" class="btn primary">📁 Gestionar Documentos</a>
          </div>
        </div>
      </section>


      <!-- 🎓 Estudiantes vinculados -->
      <section class="card">
        <header>🎓 Estudiantes que realizaron Residencia</header>
        <div class="content">
          <table>
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Carrera</th>
                <th>Periodo</th>
                <th>Proyecto</th>
                <th>Resultado</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Ana Rodríguez</td>
                <td>Informática</td>
                <td>Feb–Jul 2025</td>
                <td>Desarrollo de sistema documental</td>
                <td><span class="badge ok">Concluido</span></td>
              </tr>
              <tr>
                <td>Juan Pérez</td>
                <td>Industrial</td>
                <td>Ago–Dic 2024</td>
                <td>Optimización de procesos</td>
                <td><span class="badge ok">Concluido</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- 🔧 Acciones -->
      <section class="card">
        <div class="content actions" style="justify-content:flex-end;">
          <a href="empresa_edit.php?id=45" class="btn primary">✏️ Editar Empresa</a>
          <a href="empresa_delete.php?id=45" class="btn danger">🗑️ Eliminar Empresa</a>
        </div>
      </section>

    </main>
  </div>
</body>

</html>
