<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Revisión de Machote · Residencias Profesionales</title>

  <!-- Estilos globales -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <!-- Estilos específicos (abajo te paso el CSS) -->
  <link rel="stylesheet" href="../../assets/css/residencias/machote_revisar.css" />
</head>
<body>
<?php
  // Parámetros de contexto (ejemplo)
  $empresaId   = isset($_GET['id_empresa']) ? (int) $_GET['id_empresa'] : 45;
  $empresaName = 'Casa del Barrio';
  $convenioId  = isset($_GET['convenio'])   ? (int) $_GET['convenio']   : 12;
  $convenioStr = '#12 (v1.2)';
?>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <!-- Topbar -->
      <header class="topbar">
        <div>
          <h2>📝 Revisión de Machote (Cláusulas)</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>›</span>
            <a href="../convenio/convenio_list.php">Convenios</a>
            <span>›</span>
            <a href="../convenio/convenio_view.php?id=<?php echo $convenioId; ?>">Convenio <?php echo $convenioStr; ?></a>
            <span>›</span>
            <span>Revisión de Machote</span>
          </nav>
        </div>
        <div class="actions" style="gap:10px; flex-wrap:wrap;">
          <a class="btn" href="../empresa/empresa_view.php?id=<?php echo $empresaId; ?>">🏢 Empresa</a>
          <a class="btn" href="../convenio/convenio_view.php?id=<?php echo $convenioId; ?>">📑 Convenio</a>
          <a class="btn" href="../documentos/documento_list.php?empresa=<?php echo $empresaId; ?>">📂 Documentos</a>
        </div>
      </header>

      <!-- Contexto -->
      <section class="card">
        <header>📌 Contexto</header>
        <div class="content">
          <div class="context-grid">
            <div><strong>Empresa:</strong> <?php echo htmlspecialchars($empresaName); ?> (ID <?php echo $empresaId; ?>)</div>
            <div><strong>Convenio:</strong> <?php echo htmlspecialchars($convenioStr); ?></div>
            <div><strong>Estado actual:</strong> <span class="badge secondary">En revisión</span></div>
            <div><strong>Última actualización:</strong> 02/10/2025</div>
          </div>
          <p class="text-muted" style="margin-top:8px">
            Registra observaciones por cláusula, su estatus y responsable. Puedes aprobar en bloque cuando todo esté correcto.
          </p>
          <div class="actions" style="justify-content:flex-start; gap:8px;">
            <button class="btn" type="button" onclick="aprobarTodo()">✅ Aprobar todo</button>
            <button class="btn" type="button" onclick="agregarFila()">➕ Agregar cláusula</button>
            <a class="btn" href="#" onclick="window.print();return false;">🖨️ Imprimir</a>
            <a class="btn" href="#" title="Próximamente">⬇️ Exportar PDF</a>
          </div>
        </div>
      </section>

      <!-- Revisión cláusula por cláusula -->
      <section class="card">
        <header>📖 Cláusulas / Observaciones</header>
        <div class="content">
          <form action="revisar_action.php" method="post">
            <input type="hidden" name="empresa_id" value="<?php echo $empresaId; ?>">
            <input type="hidden" name="convenio_id" value="<?php echo $convenioId; ?>">

            <div class="table-wrapper">
              <table id="tabla-clausulas">
                <thead>
                  <tr>
                    <th style="width:60px;">#</th>
                    <th style="min-width:220px;">Título de cláusula</th>
                    <th>Texto del machote</th>
                    <th>Comentario / Observación</th>
                    <th style="min-width:140px;">Estatus</th>
                    <th style="min-width:140px;">Responsable</th>
                    <th style="width:90px;">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Ejemplos (luego dinámico desde BD) -->
                  <tr>
                    <td><input type="number" name="clausulas[0][numero]" value="1" class="inp small" min="1"/></td>
                    <td><input type="text" name="clausulas[0][titulo]" value="Objeto" class="inp" /></td>
                    <td>
                      <textarea name="clausulas[0][texto]" class="inp mono" rows="4">El presente convenio tiene por objeto establecer las bases...</textarea>
                    </td>
                    <td>
                      <textarea name="clausulas[0][comentario]" class="inp" rows="3" placeholder="Ej: Solicitan especificar KPIs y alcance."></textarea>
                    </td>
                    <td>
                      <select name="clausulas[0][estatus]" class="inp">
                        <option value="en_revision" selected>En revisión</option>
                        <option value="aprobado">Aprobado</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="rechazado">Rechazado</option>
                      </select>
                    </td>
                    <td><input type="text" name="clausulas[0][responsable]" value="Jurídico" class="inp" /></td>
                    <td class="row-actions">
                      <button class="btn small" type="button" onclick="aprobarFila(this)">✔</button>
                      <button class="btn small danger" type="button" onclick="eliminarFila(this)">🗑️</button>
                    </td>
                  </tr>

                  <tr>
                    <td><input type="number" name="clausulas[1][numero]" value="2" class="inp small" min="1"/></td>
                    <td><input type="text" name="clausulas[1][titulo]" value="Confidencialidad" class="inp" /></td>
                    <td>
                      <textarea name="clausulas[1][texto]" class="inp mono" rows="4">Las partes acuerdan mantener en estricta confidencialidad...</textarea>
                    </td>
                    <td>
                      <textarea name="clausulas[1][comentario]" class="inp" rows="3" placeholder="Ej: Sin observaciones."></textarea>
                    </td>
                    <td>
                      <select name="clausulas[1][estatus]" class="inp">
                        <option value="en_revision">En revisión</option>
                        <option value="aprobado" selected>Aprobado</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="rechazado">Rechazado</option>
                      </select>
                    </td>
                    <td><input type="text" name="clausulas[1][responsable]" value="Vinculación" class="inp" /></td>
                    <td class="row-actions">
                      <button class="btn small" type="button" onclick="aprobarFila(this)">✔</button>
                      <button class="btn small danger" type="button" onclick="eliminarFila(this)">🗑️</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="actions" style="margin-top:12px;">
              <a href="../convenio/convenio_view.php?id=<?php echo $convenioId; ?>" class="btn">⬅ Cancelar</a>
              <button type="submit" class="btn primary">💾 Guardar revisión</button>
            </div>
          </form>
        </div>
      </section>

      <!-- Bitácora -->
      <section class="card">
        <header>🕒 Bitácora</header>
        <div class="content">
          <ul class="history-list">
            <li><strong>02/10/2025</strong> — Se marcó cláusula 2 como “Aprobado”.</li>
            <li><strong>01/10/2025</strong> — Se agregaron observaciones a cláusula 1.</li>
            <li><strong>30/09/2025</strong> — Inició revisión de machote.</li>
          </ul>
        </div>
      </section>
    </main>
  </div>

  <script>
    function aprobarTodo(){
      document.querySelectorAll('#tabla-clausulas select').forEach(sel=>{
        sel.value = 'aprobado';
      });
    }
    function aprobarFila(btn){
      const tr = btn.closest('tr');
      const sel = tr.querySelector('select');
      if(sel) sel.value = 'aprobado';
    }
    function eliminarFila(btn){
      const tr = btn.closest('tr');
      tr.parentNode.removeChild(tr);
      renumerar();
    }
    function agregarFila(){
      const tbody = document.querySelector('#tabla-clausulas tbody');
      const idx = tbody.querySelectorAll('tr').length;
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><input type="number" name="clausulas[${idx}][numero]" value="${idx+1}" class="inp small" min="1"/></td>
        <td><input type="text" name="clausulas[${idx}][titulo]" class="inp" placeholder="Título de cláusula" /></td>
        <td><textarea name="clausulas[${idx}][texto]" class="inp mono" rows="3" placeholder="Texto del machote..."></textarea></td>
        <td><textarea name="clausulas[${idx}][comentario]" class="inp" rows="3" placeholder="Comentario / ajuste propuesto..."></textarea></td>
        <td>
          <select name="clausulas[${idx}][estatus]" class="inp">
            <option value="en_revision" selected>En revisión</option>
            <option value="aprobado">Aprobado</option>
            <option value="pendiente">Pendiente</option>
            <option value="rechazado">Rechazado</option>
          </select>
        </td>
        <td><input type="text" name="clausulas[${idx}][responsable]" class="inp" placeholder="Área / persona" /></td>
        <td class="row-actions">
          <button class="btn small" type="button" onclick="aprobarFila(this)">✔</button>
          <button class="btn small danger" type="button" onclick="eliminarFila(this)">🗑️</button>
        </td>
      `;
      tbody.appendChild(tr);
    }
    function renumerar(){
      document.querySelectorAll('#tabla-clausulas tbody tr').forEach((tr,i)=>{
        const num = tr.querySelector('input[type="number"]');
        if(num) num.value = i+1;
      });
    }
  </script>
</body>
</html>
