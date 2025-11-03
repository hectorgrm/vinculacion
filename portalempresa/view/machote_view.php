<?php /* require_once __DIR__ . '/../../common/auth_portal.php'; */ ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal Empresa · Revisión de Acuerdo</title>

  <!-- Reutiliza tu CSS existente si ya lo tienes -->
  <link rel="stylesheet" href="assets/css/portal/machoteview.css">

</head>
<body>

<header class="portal-header">
  <div class="brand">
    <div class="logo"></div>
    <div>
      <h1>Portal de Empresa</h1>
      <small>Revisión del acuerdo (machote)</small>
    </div>
  </div>
  <div class="userbox">
    <span class="company">Casa del Barrio</span>
    <a href="../portalacceso/portal_list.php" class="btn small">Inicio</a>
    <a href="../../common/logout.php" class="btn small">Salir</a>
  </div>
</header>

<main class="layout">

  <!-- Columna izquierda: PDF + Resumen -->
  <section class="left">

    <!-- Estado general -->
    <div class="card">
      <header>Estado general</header>
      <div class="content kpis" style="grid-template-columns: repeat(4, 1fr);">
        <div class="metric">
          <div class="num">1</div>
          <div class="lbl">Comentarios pendientes</div>
        </div>
        <div class="metric">
          <div class="num">3</div>
          <div class="lbl">Comentarios atendidos</div>
        </div>
        <div class="metric">
          <div class="num">75%</div>
          <div class="lbl">Avance de revisión</div>
        </div>
        <div class="metric">
          <div class="status-text">
            <span class="dot"></span> En revisión
          </div>
          <!-- Cuando esté aprobado:
          <div class="status-text ok">
            <span class="dot"></span> Aprobado
          </div>
          -->
        </div>
      </div>
      <div class="content">
        <div class="progressbox">
          <div class="progress"><div class="bar" style="width: 75%"></div></div>
          <small class="helper">El avance aumenta conforme se atienden los comentarios.</small>
        </div>
      </div>
    </div>

    <!-- Documento a revisar -->
    <div class="card">
      <header>Documento a revisar: Institucional v1.2</header>
      <div class="content">
        <div class="pdf-frame">
          <iframe src="../../uploads/machote_v12_borrador.pdf#view=FitH" title="Acuerdo PDF"></iframe>
        </div>
        <div class="file-actions">
          <a class="btn" href="../../uploads/machote_v12_borrador.pdf" target="_blank">📄 Abrir en nueva pestaña</a>
          <a class="btn" download href="../../uploads/machote_v12_borrador.pdf">⬇️ Descargar PDF</a>
        </div>
        <small class="note">Si no puedes ver el PDF aquí, abre en nueva pestaña o descárgalo.</small>
      </div>
    </div>

    <!-- Confirmación de la empresa -->
    <div class="card">
      <header>Confirmación de Empresa</header>
      <div class="content approval">
        <label class="switch">
          <input type="checkbox" id="aprob_empresa" />
          <span class="slider"></span>
          <span class="label">Estoy de acuerdo con el contenido del documento</span>
        </label>
        <p class="help">
          Marca esta opción cuando no tengas observaciones pendientes y estés de acuerdo con la versión final.
        </p>
        <div class="actions">
          <button class="btn primary">💾 Guardar confirmación</button>
        </div>
      </div>
    </div>

  </section>

  <!-- Columna derecha: Conversación (foro de comentarios) -->
  <section class="right">

    <!-- Crear nuevo comentario -->
    <div class="card">
      <header>Agregar comentario</header>
      <div class="content">
        <form class="new-thread">
          <div class="field">
            <label for="asunto">Tema</label>
            <input type="text" id="asunto" placeholder="Ej. Ajuste en la vigencia" required>
          </div>
          <div class="field">
            <label for="cuerpo">Descripción</label>
            <textarea id="cuerpo" rows="4" placeholder="Explica tu observación o propuesta" required></textarea>
          </div>
          <div class="field">
            <label for="adjunto">Adjunto (opcional)</label>
            <input type="file" id="adjunto">
          </div>
          <div class="actions">
            <button class="btn primary" type="submit">Publicar</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Lista de comentarios -->
    <div class="card">
      <header>Comentarios recientes</header>
      <div class="content threads">

        <!-- Comentario con seguimiento (pendiente) -->
        <article class="thread">
          <div class="meta">
            <span class="badge abierto">Pendiente</span>
            <span class="author empresa">Enviado por la Empresa</span>
            <span class="time">hace 2 días</span>
          </div>
          <h4>Ajuste en la vigencia</h4>
          <p>Proponemos 18 meses por motivos de calendario.</p>

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
              <p>Recibido. Podemos ajustarlo a 18 meses con cortes trimestrales.</p>
            </div>
          </div>

          <!-- Responder -->
          <form class="reply">
            <label for="r1">Responder</label>
            <textarea id="r1" rows="3" placeholder="Escribe tu respuesta…"></textarea>
            <div class="row">
              <input type="file">
              <div class="actions">
                <button class="btn">Adjuntar</button>
                <button class="btn primary">Enviar</button>
              </div>
            </div>
          </form>
        </article>

        <!-- Comentario atendido -->
        <article class="thread">
          <div class="meta">
            <span class="badge resuelto">Atendido</span>
            <span class="author admin">Respondido por Residencias</span>
            <span class="time">hace 5 días</span>
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

      </div>
    </div>

  </section>
</main>

<footer class="portal-foot">
  <small>Portal de Empresa · Universidad · Área de Residencias</small>
</footer>

</body>
</html>
