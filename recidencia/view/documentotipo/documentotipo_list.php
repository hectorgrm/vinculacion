<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tipos de Documento · Residencias Profesionales</title>

  <!-- Estilos globales -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/documentotipo/documentotipo.css" />

  <style>
    /* 🎨 Estilos locales */
    .filters {
      display: flex;
      flex-wrap: wrap;
      align-items: flex-end;
      gap: 15px;
    }

    .filters .field {
      flex: 1;
      min-width: 200px;
    }

    .table-wrapper {
      overflow-x: auto;
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
    .badge.secondary { background: #e2e3e5; color: #383d41; }

    .actions {
      display: flex;
      gap: 8px;
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
          <h2>📄 Tipos de Documento</h2>
          <p class="subtitle">Catálogo de documentos requeridos por Vinculación para empresas físicas y morales.</p>
        </div>
        <a href="documentotipo_add.php" class="btn primary">➕ Nuevo Tipo</a>
      </header>

      <!-- 🔎 Filtros -->
      <section class="card">
        <header>🔎 Filtros de búsqueda</header>
        <div class="content">
          <form class="form">
            <div class="filters">
              <div class="field">
                <label for="q">Buscar</label>
                <input id="q" name="q" type="text" placeholder="Nombre o descripción del documento" />
              </div>

              <div class="field">
                <label for="tipo_empresa">Tipo de empresa</label>
                <select id="tipo_empresa" name="tipo_empresa">
                  <option value="">Todas</option>
                  <option value="fisica">Persona Física</option>
                  <option value="moral">Persona Moral</option>
                  <option value="ambas">Ambas</option>
                </select>
              </div>

              <div class="actions">
                <button class="btn primary" type="submit">Buscar</button>
                <a class="btn" href="documentotipo_list.php">Limpiar</a>
              </div>
            </div>
          </form>
        </div>
      </section>

      <!-- 📋 Listado -->
      <section class="card">
        <header>📋 Listado de Tipos de Documento</header>
        <div class="content">
          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre del Documento</th>
                  <th>Descripción</th>
                  <th>Tipo de Empresa</th>
                  <th>Obligatorio</th>
                  <th style="min-width:200px;">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <!-- 🧱 Ejemplos estáticos (se generarán dinámicos desde rp_documento_tipo) -->
                <tr>
                  <td>15</td>
                  <td>Constancia de situación fiscal (SAT)</td>
                  <td>Copia de constancia del SAT emitida por la autoridad fiscal.</td>
                  <td><span class="badge secondary">Ambas</span></td>
                  <td><span class="badge ok">Sí</span></td>
                  <td class="actions">
                    <a class="btn small" href="documentotipo_edit.php?id=15">✏️ Editar</a>
                    <a class="btn small danger" href="documentotipo_delete.php?id=15">🗑️ Eliminar</a>
                  </td>
                </tr>
                <tr>
                  <td>21</td>
                  <td>INE del representante legal</td>
                  <td>Identificación oficial vigente del representante legal.</td>
                  <td><span class="badge secondary">Moral</span></td>
                  <td><span class="badge ok">Sí</span></td>
                  <td class="actions">
                    <a class="btn small" href="documentotipo_edit.php?id=21">✏️ Editar</a>
                    <a class="btn small danger" href="documentotipo_delete.php?id=21">🗑️ Eliminar</a>
                  </td>
                </tr>
                <tr>
                  <td>18</td>
                  <td>Logotipo del negocio</td>
                  <td>Archivo JPG o PNG con el logotipo del negocio (opcional).</td>
                  <td><span class="badge secondary">Física</span></td>
                  <td><span class="badge warn">No</span></td>
                  <td class="actions">
                    <a class="btn small" href="documentotipo_edit.php?id=18">✏️ Editar</a>
                    <a class="btn small danger" href="documentotipo_delete.php?id=18">🗑️ Eliminar</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="legend">
            <strong>Leyenda:</strong>
            <span class="badge ok">Sí (Obligatorio)</span>
            <span class="badge warn">No (Opcional)</span>
          </div>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
