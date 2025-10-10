<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Documentos · Residencias Profesionales</title>

  <!-- Estilos globales -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
        <link rel="stylesheet" href="../../assets/css/documentos/documento_list.css" />

  <!-- (Opcional) Estilos específicos de documentos -->
  <!-- <link rel="stylesheet" href="../../assets/css/residencias/documento_list.css" /> -->
</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <div>
          <h2>📂 Gestión de Documentos</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>›</span>
            <span>Documentos</span>
          </nav>
        </div>
        <div class="actions" style="gap:10px;">
          <!-- Subida directa (general) -->
          <a href="documento_upload.php" class="btn primary">⬆️ Subir Documento</a>
        </div>
      </header>

      <!-- (Opcional) Contexto si se filtra por empresa/convenio -->
      <!--
      <section class="card">
        <header>📌 Contexto</header>
        <div class="content">
          Mostrando documentos de la empresa <strong>Casa del Barrio</strong> (ID #45)
          y convenio <strong>#12</strong>.
          <div class="actions" style="margin-top:10px; justify-content:flex-start;">
            <a class="btn" href="../empresa/empresa_view.php?id=45">🏢 Ver empresa</a>
            <a class="btn" href="../convenio/convenio_view.php?id=12">📑 Ver convenio</a>
            <a class="btn" href="documento_upload.php?empresa=45&convenio=12">⬆️ Subir ligado</a>
          </div>
        </div>
      </section>
      -->

      <section class="card">
        <header>📋 Listado de Documentos</header>
        <div class="content">
          <!-- Filtros -->
          <form method="GET" class="form" style="margin-bottom: 14px;">
            <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
              <div class="field" style="min-width:260px; max-width:360px; flex:1;">
                <label for="q">Buscar</label>
                <input type="text" id="q" name="q" placeholder="Empresa, tipo, notas..." />
              </div>

              <div class="field">
                <label for="empresa">Empresa</label>
                <select id="empresa" name="empresa">
                  <option value="">Todas</option>
                  <option value="45">Casa del Barrio</option>
                  <option value="22">Tequila ECT</option>
                  <option value="31">Industrias Yakumo</option>
                </select>
              </div>

              <div class="field">
                <label for="convenio">Convenio</label>
                <select id="convenio" name="convenio">
                  <option value="">Todos</option>
                  <option value="12">#12 (v1.2)</option>
                  <option value="15">#15 (v2.0)</option>
                </select>
              </div>

              <div class="field">
                <label for="tipo">Tipo</label>
                <select id="tipo" name="tipo">
                  <option value="">Todos</option>
                  <option value="INE">INE Representante</option>
                  <option value="ACTA">Acta Constitutiva</option>
                  <option value="ANEXO">Anexo Técnico</option>
                </select>
              </div>

              <div class="field">
                <label for="estatus">Estatus</label>
                <select id="estatus" name="estatus">
                  <option value="">Todos</option>
                  <option value="aprobado">Aprobado</option>
                  <option value="pendiente">Pendiente</option>
                  <option value="rechazado">Rechazado</option>
                </select>
              </div>

              <div class="actions" style="margin:0;">
                <button type="submit" class="btn primary">🔎 Buscar</button>
                <a href="documento_list.php" class="btn">Limpiar</a>
              </div>
            </div>
          </form>

          <!-- Tabla -->
          <div class="table-wrapper" style="overflow:auto;">
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Empresa</th>
                  <th>Convenio</th>
                  <th>Tipo</th>
                  <th>Estatus</th>
                  <th>Fecha</th>
                  <th style="min-width:260px;">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <!-- Ejemplos (luego dinámico con PHP) -->
                <tr>
                  <td>101</td>
                  <td><a class="btn" href="../empresa/empresa_view.php?id=45">Casa del Barrio</a></td>
                  <td><a class="btn" href="../convenio/convenio_view.php?id=12">#12 (v1.2)</a></td>
                  <td>INE Representante</td>
                  <td><span class="badge ok">Aprobado</span></td>
                  <td>2025-09-10</td>
                  <td class="actions" style="display:flex; gap:8px; flex-wrap:wrap;">
                    <a class="btn" href="../../uploads/ine_josevelador.pdf" target="_blank">📄 Ver</a>
                    <a class="btn" href="documento_view.php?id=101">👁️ Detalle</a>
                    <a class="btn" href="documento_delete.php?id=101">🗑️ Eliminar</a>
                  </td>
                </tr>

                <tr>
                  <td>102</td>
                  <td><a class="btn" href="../empresa/empresa_view.php?id=45">Casa del Barrio</a></td>
                  <td><a class="btn" href="../convenio/convenio_view.php?id=12">#12 (v1.2)</a></td>
                  <td>Acta Constitutiva</td>
                  <td><span class="badge warn">Pendiente</span></td>
                  <td>—</td>
                  <td class="actions" style="display:flex; gap:8px; flex-wrap:wrap;">
                    <a class="btn primary" href="documento_upload.php?empresa=45&convenio=12&tipo=ACTA">⬆️ Subir</a>
                    <a class="btn" href="documento_view.php?id=102">👁️ Detalle</a>
                    <a class="btn" href="documento_delete.php?id=102">🗑️ Eliminar</a>
                  </td>
                </tr>

                <tr>
                  <td>103</td>
                  <td><a class="btn" href="../empresa/empresa_view.php?id=31">Industrias Yakumo</a></td>
                  <td>—</td>
                  <td>Anexo Técnico</td>
                  <td><span class="badge secondary">Rechazado</span></td>
                  <td>2025-08-22</td>
                  <td class="actions" style="display:flex; gap:8px; flex-wrap:wrap;">
                    <a class="btn" href="../../uploads/anexo_tecnico_103.pdf" target="_blank">📄 Ver</a>
                    <a class="btn" href="documento_view.php?id=103">👁️ Detalle</a>
                    <a class="btn" href="documento_delete.php?id=103">🗑️ Eliminar</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Leyenda -->
          <div style="margin-top:10px; color:#64748b; font-size:14px;">
            <strong>Leyenda:</strong>
            <span class="badge ok">Aprobado</span>
            <span class="badge warn">Pendiente</span>
            <span class="badge secondary">Rechazado</span>
          </div>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
