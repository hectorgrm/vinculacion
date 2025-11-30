<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Portal Empresa · Acuerdo Aprobado</title>

  <link rel="stylesheet" href="../assets/css/modules/machoteaprobado.css">
  


  <style>
    /* Pequeños ajustes para estado aprobado */
    .status-text.ok .dot {
      background: #16a34a;
    }

    .status-strip {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 12px;
      border: 1px solid #bbf7d0;
      background: #ecfdf5;
      border-radius: 12px;
      margin-bottom: 10px
    }

    .status-strip .big {
      font-weight: 800;
      color: #166534
    }

    .readonly-note {
      color: #475569;
      font-size: 13px
    }
    
  </style>

</head>

<body>

  <header class="portal-header">
    <div class="brand">
      <div class="logo"></div>
      <div>
        <h1>Portal de Empresa</h1>
        <small>Acuerdo aprobado</small>
      </div>
    </div>
    <div class="userbox">
      <span class="company">Casa del Barrio</span>
      <a href="portal_list.php" class="btn small">Inicio</a>
      <a href="../../common/logout.php" class="btn small">Salir</a>
    </div>
  </header>

  <main class="layout">

    <!-- Columna izquierda -->
    <section class="left">

      <!-- Estado general -->
      <div class="card">
        <header>Estado general</header>
        <div class="content">
          <div class="status-strip">
            <span class="badge ok">Aprobado</span>
            <span class="big">El documento ha sido aprobado por ambas partes</span>
          </div>

          <div class="kpis" style="grid-template-columns: repeat(3, 1fr);">
            <div class="kpi">
              <div class="kpi-num">0</div>
              <div class="kpi-label">Comentarios pendientes</div>
            </div>
            <div class="kpi">
              <div class="kpi-num">4</div>
              <div class="kpi-label">Comentarios atendidos</div>
            </div>
            <div class="kpi">
              <div class="kpi-num">100%</div>
              <div class="kpi-label">Avance de revisión</div>
            </div>
          </div>

          <div class="progressbox" style="margin-top:10px;">
            <div class="progress">
              <div class="bar" style="width: 100%"></div>
            </div>
            <small class="helper">La revisión se completó correctamente.</small>
          </div>
        </div>
      </div>

      <!-- Documento final -->
      <div class="card">
        <header>Documento final: Institucional v1.2</header>
        <div class="content">
          <div class="pdf-frame">
            <iframe src="../../uploads/machote_v12_final.pdf#view=FitH" title="Documento final PDF"></iframe>
          </div>
          <div class="file-actions">
            <a class="btn" href="../../uploads/machote_v12_final.pdf" target="_blank">📄 Abrir en nueva pestaña</a>
            <a class="btn" download href="../../uploads/machote_v12_final.pdf">⬇️ Descargar PDF</a>
          </div>
          <small class="note">Este es el documento acordado por ambas partes.</small>
        </div>
      </div>

      <!-- Confirmación de la empresa (bloqueada) -->
      <div class="card">
        <header>Confirmación de Empresa</header>
        <div class="content approval">
          <label class="switch">
            <input type="checkbox" id="aprob_empresa" checked disabled />
            <span class="slider"></span>
            <span class="label">Estoy de acuerdo con el contenido del documento</span>
          </label>
          <p class="readonly-note">La confirmación ya fue registrada. Si necesitas cambios, contacta a Residencias.</p>
          <div class="actions">
            <a class="btn" href="../convenio/convenio_view.php?id=12">📑 Ver convenio generado</a>
          </div>
        </div>
      </div>

    </section>

    <!-- Columna derecha -->
    <section class="right">

      <!-- Resumen del intercambio (solo lectura) -->
      <div class="card">
        <header>Historial de comentarios</header>
        <div class="content threads">
          <article class="thread">
            <div class="meta">
              <span class="badge resuelto">Atendido</span>
              <span class="author empresa">Enviado por la Empresa</span>
              <span class="time">hace 8 días</span>
            </div>
            <h4>Ajuste en la vigencia</h4>
            <p>Se acordó una duración de 18 meses con cortes trimestrales.</p>

            <div class="messages">
              <div class="message">
                <div class="head">
                  <span class="pill empresa">Empresa</span>
                  <time>2025-10-01 10:12</time>
                </div>
                <p>Solicitamos que la duración sea de 18 meses para cubrir un ciclo completo.</p>
                <div class="files"><a href="#">propuesta_fechas.pdf</a></div>
              </div>

              <div class="message">
                <div class="head">
                  <span class="pill admin">Residencias</span>
                  <time>2025-10-01 12:20</time>
                </div>
                <p>Se ajustó a 18 meses y se establecieron cortes trimestrales.</p>
              </div>
            </div>
          </article>

          <article class="thread">
            <div class="meta">
              <span class="badge resuelto">Atendido</span>
              <span class="author admin">Respondido por Residencias</span>
              <span class="time">hace 10 días</span>
            </div>
            <h4>Confidencialidad</h4>
            <p>Se integró el anexo de confidencialidad solicitado.</p>

            <div class="messages">
              <div class="message">
                <div class="head">
                  <span class="pill empresa">Empresa</span>
                  <time>2025-09-29 09:40</time>
                </div>
                <p>Por favor reforzar el apartado de terceros.</p>
              </div>
              <div class="message">
                <div class="head">
                  <span class="pill admin">Residencias</span>
                  <time>2025-09-29 13:10</time>
                </div>
                <p>Listo: se agregó anexo y referencia a terceros vinculados.</p>
              </div>
            </div>
          </article>

          <!-- Puedes listar más comentarios resueltos... -->

        </div>
      </div>

      <!-- Mensaje final -->
      <div class="card">
        <header>¿Qué sigue?</header>
        <div class="content">
          <p>El documento ya está aprobado. Puedes revisar el convenio generado o descargar el PDF final.</p>
          <div class="actions">
            <a class="btn primary" href="../convenio/convenio_view.php?id=12">📑 Abrir convenio</a>
            <a class="btn" href="../../uploads/machote_v12_final.pdf" download>⬇️ Descargar acuerdo</a>
          </div>
        </div>
      </div>

    </section>
  </main>

  <footer class="portal-foot">
    <small>Portal de Empresa · Universidad · Área de Residencias</small>
  </footer>

</body>

</html>
