<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>➕ Nuevo Machote · Residencias</title>

  <!-- Estilos principales -->
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <link rel="stylesheet" href="../../assets/css/modules/machote.css" />
  <style>
    /* ==== machote_add.css ==== */
    .card {
      background: #fff;
      border-radius: 16px;
      padding: 24px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.05);
      margin-top: 20px;
    }

    .form-grid {
      display: grid;
      gap: 20px;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .form-group.full {
      grid-column: 1 / -1;
    }

    .form-group label {
      font-weight: 600;
      color: #334155;
      font-size: 15px;
    }

    .form-group input[type="text"],
    .form-group select,
    .form-group input[type="file"] {
      padding: 10px 12px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      font-size: 15px;
      background: #f9fafb;
    }

    .form-group input[type="file"] {
      background: #fff;
    }

    .form-group small {
      color: #64748b;
      font-size: 13px;
    }

    .form-actions {
      display: flex;
      justify-content: flex-end;
      gap: 12px;
      grid-column: 1 / -1;
      margin-top: 8px;
    }

    .subtitle {
      color: #475569;
      margin-top: 4px;
    }

    .btn.primary {
      background: linear-gradient(135deg, #2563eb, #1e40af);
      border: none;
      color: white;
      font-weight: 600;
      padding: 10px 18px;
      border-radius: 10px;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn.primary:hover {
      background: linear-gradient(135deg, #1d4ed8, #1e3a8a);
    }

    .btn.secondary {
      background: #f1f5f9;
      color: #1e293b;
      border-radius: 10px;
      padding: 10px 18px;
      font-weight: 500;
      border: 1px solid #cbd5e1;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn.secondary:hover {
      background: #e2e8f0;
    }

    @media (max-width: 700px) {
      .form-grid {
        grid-template-columns: 1fr;
      }
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
          <h2>➕ Registrar nuevo Machote</h2>
          <p class="subtitle">
            Crea una nueva revisión de machote para una empresa y da inicio al proceso de aprobación.
          </p>
        </div>
      </header>

      <section class="card">
        <form action="#" method="post" enctype="multipart/form-data" class="form-grid">
          <!-- Empresa -->
          <div class="form-group">
            <label for="empresa_id">Empresa</label>
            <select name="empresa_id" id="empresa_id" required>
              <option value="">-- Selecciona una empresa --</option>
              <option value="1">Casa del Barrio</option>
              <option value="2">Tequila ECT</option>
              <option value="3">Industrias Yakumo</option>
            </select>
          </div>

          <!-- Nombre -->
          <div class="form-group">
            <label for="nombre_machote">Nombre del machote</label>
            <input type="text" name="nombre_machote" id="nombre_machote" placeholder="Ej. Machote institucional base" required>
          </div>

          <!-- Versión -->
          <div class="form-group">
            <label for="version_machote">Versión</label>
            <input type="text" name="version_machote" id="version_machote" placeholder="Ej. v1.0" required>
          </div>

          <!-- Tipo -->
          <div class="form-group">
            <label for="tipo">Tipo de machote</label>
            <select name="tipo" id="tipo">
              <option value="institucional">Institucional</option>
              <option value="empresa">Empresa</option>
            </select>
          </div>

          <!-- Archivo -->
          <div class="form-group full">
            <label for="archivo">Archivo inicial</label>
            <input type="file" name="archivo" id="archivo" accept=".pdf,.doc,.docx" required>
            <small>Formatos permitidos: PDF o DOCX · Máx. 10 MB</small>
          </div>

          <!-- Acciones -->
          <div class="form-actions">
            <button type="submit" class="btn primary">💾 Guardar Machote</button>
            <a href="machote_list.php" class="btn secondary">Cancelar</a>
          </div>
        </form>
      </section>
    </main>
  </div>
</body>
</html>
