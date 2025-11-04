<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal Empresa · Documentos</title>
  <link rel="stylesheet" href="../assets/css/documentos/documento_list.css" />
</head>
<body>

<?php
// ---------------------------------------------------------
// 🔹 Placeholders — se conectarán más adelante con el Handler y Controller
// ---------------------------------------------------------

// (Ejemplo: datos que vendrán de $_SESSION o del Controller)
$empresaNombre = 'Casa del Barrio'; // ← vendrá de $_SESSION['empresa_nombre']

// Placeholder de documentos: esto luego se poblará desde el Model con JOIN a:
// rp_empresa_doc + rp_documento_tipo + rp_documento_tipo_empresa
$documentos = [
  [
    'id' => 1,
    'nombre_documento' => 'INE Representante',
    'tipo' => 'Global',
    'estatus' => 'Aprobado',
    'actualizado_en' => '2025-09-10',
    'observaciones' => null,
    'archivo_path' => 'uploads/ine_representante.pdf'
  ],
  [
    'id' => 2,
    'nombre_documento' => 'Acta Constitutiva',
    'tipo' => 'Global',
    'estatus' => 'Pendiente',
    'actualizado_en' => null,
    'observaciones' => 'Falta sello legible',
    'archivo_path' => null
  ],
  [
    'id' => 3,
    'nombre_documento' => 'Poder Notarial',
    'tipo' => 'Personalizado',
    'estatus' => 'Rechazado',
    'actualizado_en' => '2025-09-01',
    'observaciones' => 'Página 2 borrosa',
    'archivo_path' => 'uploads/poder_notarial.pdf'
  ]
];

// KPIs calculados dinámicamente (se mantendrán igual en el Controller)
$kpiOk   = count(array_filter($documentos, fn($d)=>$d['estatus']==='Aprobado'));
$kpiPend = count(array_filter($documentos, fn($d)=>$d['estatus']==='Pendiente'));
$kpiRech = count(array_filter($documentos, fn($d)=>$d['estatus']==='Rechazado'));
?>

<!-- ======================================================= -->
<!-- ENCABEZADO PORTAL -->
<!-- ======================================================= -->
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

<!-- ======================================================= -->
<!-- CONTENIDO PRINCIPAL -->
<!-- ======================================================= -->
<main class="container">

  <section class="titlebar">
    <div>
      <h1>📂 Documentos de la empresa</h1>
      <p>Consulta los documentos solicitados y sube tu versión actualizada si fue requerida por el área de Vinculación.</p>
    </div>
    <div class="actions">
      <a href="convenio_view.php" class="btn">📑 Ver convenio</a>
    </div>
  </section>

  <section class="grid">
    <!-- COLUMNA IZQUIERDA -->
    <div class="col">
      <!-- KPIs -->
      <div class="card">
        <header>Resumen</header>
        <div class="content">
          <div class="kpis">
            <div class="kpi"><div class="num"><?= $kpiOk ?></div><div class="lbl">Aprobados</div></div>
            <div class="kpi"><div class="num"><?= $kpiPend ?></div><div class="lbl">Pendientes</div></div>
            <div class="kpi"><div class="num"><?= $kpiRech ?></div><div class="lbl">Rechazados</div></div>
          </div>
        </div>
      </div>

      <!-- LISTADO -->
      <div class="card">
        <header>Listado de documentos</header>
        <div class="content">

          <!-- FILTROS -->
          <form class="filters" method="get" action="">
            <div class="field">
              <label for="q">Buscar</label>
              <input type="text" id="q" name="q" placeholder="Nombre del documento…">
            </div>
            <div class="field">
              <label for="estado">Estado</label>
              <select id="estado" name="estado">
                <option value="">Todos</option>
                <option value="Aprobado">Aprobado</option>
                <option value="Pendiente">Pendiente</option>
                <option value="Rechazado">Rechazado</option>
              </select>
            </div>
            <button class="btn primary" type="submit">🔎 Filtrar</button>
          </form>

          <!-- TABLA -->
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Documento</th>
                  <th>Tipo</th>
                  <th>Estado</th>
                  <th>Última actualización</th>
                  <th>Observaciones</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($documentos as $d): ?>
                <tr>
                  <td><?= htmlspecialchars($d['nombre_documento']) ?></td>
                  <td><?= htmlspecialchars($d['tipo']) ?></td>
                  <td>
                    <?php if($d['estatus']==='Aprobado'): ?>
                      <span class="badge ok">Aprobado</span>
                    <?php elseif($d['estatus']==='Pendiente'): ?>
                      <span class="badge warn">Pendiente</span>
                    <?php else: ?>
                      <span class="badge danger">Rechazado</span>
                    <?php endif; ?>
                  </td>
                  <td><?= $d['actualizado_en'] ? htmlspecialchars($d['actualizado_en']) : '—' ?></td>
                  <td><?= $d['observaciones'] ? htmlspecialchars($d['observaciones']) : '—' ?></td>
                  <td class="actions">
                    <?php if($d['archivo_path']): ?>
                      <a class="btn small" href="../../<?= htmlspecialchars($d['archivo_path']) ?>" target="_blank">📄 Ver</a>
                      <a class="btn small" href="../../<?= htmlspecialchars($d['archivo_path']) ?>" download>⬇️ Descargar</a>
                    <?php else: ?>
                      <span class="hint">Sin archivo</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>

    <!-- COLUMNA DERECHA -->
    <div class="col">
      <div class="card" id="subir">
        <header>Subir / Actualizar documento</header>
        <div class="content">
          <p class="hint">Solo usa este formulario si Residencias te solicitó reemplazar un documento pendiente o rechazado.</p>

          <!-- FUTURE: Se conectará con empresa_documento_upload_handler.php -->
          <form class="upload" method="post" enctype="multipart/form-data" action="../handler/empresa_documento_upload_handler.php">
            <div class="field">
              <label for="doc_tipo">Tipo de documento</label>
              <select id="doc_tipo" name="doc_tipo" required>
                <option value="">Selecciona…</option>
                <!-- Placeholder dinámico: se llenará desde rp_documento_tipo y rp_documento_tipo_empresa -->
                <?php /*
                  foreach ($tiposDocumentos as $tipo) {
                    echo "<option value='{$tipo['id']}'>" . htmlspecialchars($tipo['nombre']) . "</option>";
                  }
                */ ?>
              </select>
            </div>

            <div class="field">
              <label for="archivo">Archivo (PDF/JPG/PNG)</label>
              <input type="file" id="archivo" name="archivo" accept=".pdf,.jpg,.jpeg,.png" required>
            </div>

            <div class="field">
              <label for="comentario">Comentario (opcional)</label>
              <textarea id="comentario" name="comentario" rows="3" placeholder="Notas para el área de Vinculación…"></textarea>
            </div>

            <div class="actions">
              <button class="btn primary" type="submit">⬆️ Subir documento</button>
              <a class="btn" href="#top">Cancelar</a>
            </div>
          </form>
        </div>
      </div>

      <div class="card">
        <header>Ayuda</header>
        <div class="content">
          <ul>
            <li>Formatos aceptados: PDF, JPG, PNG.</li>
            <li>Tamaño máximo recomendado: 10 MB.</li>
            <li>Revisa las observaciones antes de subir nuevamente un documento rechazado.</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

</main>

</body>
</html>
