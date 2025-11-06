<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>🔁 Renovar Convenio · Residencias Profesionales</title>

  <style>
    /* ==== ESTILOS BASE ==== */
    body {
      font-family: "Segoe UI", Roboto, Arial, sans-serif;
      background-color: #f5f7fa;
      margin: 0;
      padding: 0;
      color: #333;
    }

    .app {
      display: flex;
      min-height: 100vh;
    }

    main.main {
      flex: 1;
      padding: 24px 32px;
    }

    .card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      padding: 24px 32px;
      max-width: 800px;
      margin: 0 auto;
    }

    .card header {
      font-size: 1.4rem;
      font-weight: 600;
      margin-bottom: 16px;
      color: #1d3557;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .text-muted {
      color: #6b7280;
      font-size: 0.9rem;
      margin-bottom: 16px;
    }

    /* ==== GRID DATOS ==== */
    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 16px;
      margin-bottom: 24px;
    }

    .field label {
      display: block;
      font-weight: 600;
      margin-bottom: 6px;
      font-size: 0.95rem;
    }

    .field input {
      width: 100%;
      padding: 8px 10px;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      font-size: 0.95rem;
      background-color: #fafafa;
    }

    .field input:read-only {
      background-color: #f3f4f6;
      color: #555;
    }

    .section-title {
      grid-column: 1 / -1;
      margin-top: 12px;
      margin-bottom: 4px;
      color: #1d3557;
      font-size: 1.1rem;
      font-weight: 600;
      border-bottom: 2px solid #e5e7eb;
      padding-bottom: 4px;
    }

    /* ==== BOTONES ==== */
    .actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 16px;
    }

    .btn {
      display: inline-block;
      padding: 10px 18px;
      border-radius: 6px;
      font-weight: 600;
      text-decoration: none;
      font-size: 0.95rem;
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .btn.primary {
      background-color: #1d3557;
      color: #fff;
      border: none;
    }

    .btn.primary:hover {
      background-color: #264b7b;
    }

    .btn.secondary {
      background-color: #e5e7eb;
      color: #333;
    }

    .btn.secondary:hover {
      background-color: #d1d5db;
    }

    /* ==== ALERTAS ==== */
    .alert {
      border-radius: 6px;
      padding: 12px 16px;
      margin-bottom: 16px;
      font-size: 0.95rem;
    }

    .alert.info {
      background-color: #e0f2fe;
      color: #0369a1;
    }

    .alert.success {
      background-color: #dcfce7;
      color: #166534;
    }

    .alert.warning {
      background-color: #fef3c7;
      color: #92400e;
    }

  </style>
</head>

<body>
  <div class="app">
    <main class="main">

      <section class="card">
        <header>🔁 Renovar Convenio</header>

        <div class="alert info">
          Este formulario permite generar una <strong>nueva versión</strong> del convenio seleccionado.<br>
          El convenio original se mantendrá en estado <strong>“solo lectura”</strong> para conservar su historial.
        </div>

        <p class="text-muted">
          Verifica los datos heredados del convenio actual y ajusta únicamente la nueva vigencia si es necesario.
        </p>

        <form>
          <div class="grid">

            <h3 class="section-title">🏢 Datos de la Empresa</h3>
            <div class="field">
              <label>Nombre de la empresa</label>
              <input type="text" value="Industrias Yakumo S.A. de C.V." readonly>
            </div>

            <div class="field">
              <label>Tipo de convenio</label>
              <input type="text" value="Residencias Profesionales" readonly>
            </div>

            <h3 class="section-title">📄 Detalles del Convenio Anterior</h3>
            <div class="field">
              <label>Versión anterior</label>
              <input type="text" value="v1.0" readonly>
            </div>

            <div class="field">
              <label>Estatus anterior</label>
              <input type="text" value="Completado" readonly>
            </div>

            <div class="field">
              <label>Vigencia anterior</label>
              <input type="text" value="2024-01-01 — 2024-12-31" readonly>
            </div>

            <h3 class="section-title">📅 Nueva Vigencia</h3>
            <div class="field">
              <label>Fecha de inicio</label>
              <input type="date" value="2025-01-01">
            </div>

            <div class="field">
              <label>Fecha de término</label>
              <input type="date" value="2025-12-31">
            </div>

            <div class="field col-span-2">
              <label>Observaciones</label>
              <input type="text" placeholder="Notas sobre esta renovación...">
            </div>
          </div>

          <div class="actions">
            <a href="convenio_list.php" class="btn secondary">⬅ Cancelar</a>
            <button type="submit" class="btn primary">💾 Generar nueva versión</button>
          </div>
        </form>
      </section>

    </main>
  </div>
</body>
</html>
