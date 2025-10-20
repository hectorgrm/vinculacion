<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Gestión de Documentos · Residencias Profesionales</title>

  <!-- Estilos globales -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/documentos/documento_list.css" />

  <style>
    /* 🎨 Estilos locales de apoyo */
    .filters {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      align-items: flex-end;
    }

    .filters .field {
      min-width: 180px;
      flex: 1;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 10px 12px;
      border-bottom: 1px solid #e0e0e0;
      text-align: left;
    }

    th {
      background: #f8f8f8;
      font-weight: 600;
    }

    .badge {
      display: inline-block;
      padding: 4px 8px;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 600;
    }

    .badge.ok { background: #d4edda; color: #155724; }
    .badge.warn { background: #fff3cd; color: #856404; }
    .badge.err { background: #f8d7da; color: #721c24; }
    .badge.secondary { background: #e2e3e5; color: #383d41; }

    .actions {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }

    .btn.small {
      font-size: 14px;
      padding: 6px 10px;
    }

    .legend {
      margin-top: 15px;
      font-size: 14px;
      color: #555;
    }

    .legend strong {
      margin-right: 6px;
    }
  </style>
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
          <p class="subtitle">Revisión, control y estado de los documentos cargados por las empresas.</p>
        </div>
        <a href="documento_upload.php" class="btn primary">⬆️ Subir Documento</a>
      </header>

      <!-- 🔍 Filtros -->
      <section class="card">
        <header>🔍 Filtros de búsqueda</header>
        <div class="content">
          <form class="form">
            <div class="filters">
              <div class="field">
                <label for="q">Buscar</label>
                <input id="q" name="q" type="text" placeholder="Empresa, tipo o nota..." />
              </div>

              <div class="field">
                <label for="empresa">Empresa</label>
                <select id="empresa" name="empresa">
                  <option value="">Todas</option>
                  <option value="1">Casa del Barrio</option>
                  <option value="2">Tequila ECT</option>
                  <option value="3">Industrias Yakumo</option>
                </select>
              </div>

              <div class="field">
                <label for="tipo">Tipo de Documento</label>
                <select id="tipo" name="tipo">
                  <option value="">Todos</option>
                  <option value="15">Constancia SAT</option>
                  <option value="16">Comprobante domicilio</option>
                  <option value="19">Acta constitutiva</option>
                  <option value="21">INE representante legal</option>
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

              <div class="actions">
                <button type="submit" class="btn primary">🔎 Buscar</button>
                <a href="documento_list.php" class="btn">Limpiar</a>
              </div>
            </div>
          </form>
        </div>
      </section>

      <!-- 📋 Listado de documentos -->
      <section class="card">
        <header>📋 Documentos registrados</header>
        <div class="content">
          <div class="table-wrapper" style="overflow-x:auto;">
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Empresa</th>
                  <th>Tipo de Documento</th>
                  <th>Estatus</th>
                  <th>Observación</th>
                  <th>Fecha de Carga</th>
                  <th style="min-width:260px;">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <!-- Ejemplos estáticos (luego dinámico con PHP) -->
                <tr>
                  <td>8</td>
                  <td><a class="btn small" href="../empresa/empresa_view.php?id=1">Casa del Barrio</a></td>
                  <td>Acta constitutiva</td>
                  <td><span class="badge ok">Aprobado</span></td>
                  <td>Documento validado correctamente.</td>
                  <td>2025-09-19</td>
                  <td class="actions">
                    <a class="btn small" href="../../uploads/docs/technova_acta.pdf" target="_blank">📄 Ver</a>
                    <a class="btn small" href="documento_view.php?id=8">👁️ Detalle</a>
                    <a class="btn small danger" href="documento_delete.php?id=8">🗑️ Eliminar</a>
                  </td>
                </tr>

                <tr>
                  <td>9</td>
                  <td><a class="btn small" href="../empresa/empresa_view.php?id=1">Casa del Barrio</a></td>
                  <td>INE del representante legal</td>
                  <td><span class="badge warn">Pendiente</span></td>
                  <td>Falta sello notarial.</td>
                  <td>2025-09-19</td>
                  <td class="actions">
                    <a class="btn small primary" href="documento_review.php?id=9">📝 Revisar</a>
                    <a class="btn small" href="documento_view.php?id=9">👁️ Detalle</a>
                  </td>
                </tr>

                <tr>
                  <td>11</td>
                  <td><a class="btn small" href="../empresa/empresa_view.php?id=2">Tequila ECT</a></td>
                  <td>Comprobante de domicilio</td>
                  <td><span class="badge err">Rechazado</span></td>
                  <td>Dirección no coincide con RFC.</td>
                  <td>2025-09-19</td>
                  <td class="actions">
                    <a class="btn small" href="../../uploads/docs/barberia_comprobante.pdf" target="_blank">📄 Ver</a>
                    <a class="btn small" href="documento_review.php?id=11">📝 Revisar</a>
                    <a class="btn small danger" href="documento_delete.php?id=11">🗑️ Eliminar</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="legend">
            <strong>Leyenda:</strong>
            <span class="badge ok">Aprobado</span>
            <span class="badge warn">Pendiente</span>
            <span class="badge err">Rechazado</span>
          </div>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
