<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Agregar Documento Individual · Residencias Profesionales</title>

  <!-- Estilos globales -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/documentotipo/documentotipo.css" />

  <style>
    /* ===== 🎨 Estilos locales ===== */
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }

    .form-grid .full {
      grid-column: 1 / -1;
    }

    .card .content label {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
      color: #333;
    }

    .card .content input,
    .card .content select,
    .card .content textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
      transition: border-color 0.2s;
    }

    .card .content input:focus,
    .card .content select:focus,
    .card .content textarea:focus {
      border-color: #007bff;
      outline: none;
    }

    textarea {
      min-height: 100px;
      resize: vertical;
    }

    .actions {
      display: flex;
      justify-content: flex-end;
      gap: 12px;
      margin-top: 20px;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 10px 16px;
      border-radius: 6px;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
      transition: background 0.2s;
    }

    .btn.primary {
      background: #007bff;
      color: #fff;
    }
    .btn.primary:hover {
      background: #0069d9;
    }

    .btn.secondary {
      background: #e0e0e0;
      color: #222;
    }
    .btn.secondary:hover {
      background: #d5d5d5;
    }

    .subtitle {
      font-size: 15px;
      color: #555;
      margin-top: 4px;
    }
  </style>
</head>

<body>
  <?php
    $empresa_id = $_GET['id_empresa'] ?? 0;
  ?>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <!-- 🔝 Header -->
      <header class="topbar">
        <div>
          <h2>➕ Nuevo Documento Individual</h2>
          <p class="subtitle">Agrega un requisito documental específico para esta empresa.</p>
        </div>
        <a href="empresa_documentotipo_list.php?id_empresa=<?= htmlspecialchars($empresa_id) ?>" class="btn secondary">⬅ Volver</a>
      </header>

      <!-- 🧾 Formulario -->
      <section class="card">
        <header>📄 Datos del Documento</header>
        <div class="content">
          <form action="empresa_documentotipo_add_action.php" method="POST">
            <input type="hidden" name="empresa_id" value="<?= htmlspecialchars($empresa_id) ?>">

            <div class="form-grid">
              <!-- nombre -->
              <div class="field full">
                <label class="required" for="nombre">Nombre del documento *</label>
                <input type="text" id="nombre" name="nombre" maxlength="100"
                  placeholder="Ejemplo: Certificado de Seguridad Interna" required>
              </div>

              <!-- descripcion -->
              <div class="field full">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion"
                  placeholder="Describe brevemente el propósito del documento o su uso."></textarea>
              </div>

              <!-- obligatorio -->
              <div class="field">
                <label class="required" for="obligatorio">¿Obligatorio? *</label>
                <select id="obligatorio" name="obligatorio" required>
                  <option value="1" selected>Sí</option>
                  <option value="0">No</option>
                </select>
              </div>

              <!-- tipo_empresa -->
              <div class="field">
                <label class="required" for="tipo_empresa">Tipo de empresa *</label>
                <select id="tipo_empresa" name="tipo_empresa" required>
                  <option value="ambas" selected>Ambas</option>
                  <option value="fisica">Física</option>
                  <option value="moral">Moral</option>
                </select>
              </div>
            </div>

            <div class="actions">
              <a href="empresa_documentotipo_list.php?id_empresa=<?= htmlspecialchars($empresa_id) ?>" class="btn secondary">Cancelar</a>
              <button type="submit" class="btn primary">💾 Guardar</button>
            </div>
          </form>
        </div>
      </section>

      <!-- ℹ️ Nota -->
      <section class="card" style="margin-top: 20px;">
        <header>ℹ️ Información</header>
        <div class="content">
          <p>
            Los <strong>documentos individuales</strong> son requisitos creados específicamente para una empresa.
            No afectan a las demás empresas del sistema.
          </p>
          <p>
            Una vez agregado, el documento aparecerá en el listado de requerimientos de la empresa y podrá recibir
            archivos, revisiones y estatus igual que los documentos globales.
          </p>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
