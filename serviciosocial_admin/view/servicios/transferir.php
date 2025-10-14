<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Transferencia de Empresa · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <style>
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }
    .form-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }
    label {
      font-weight: 600;
      color: var(--muted);
    }
    input, select, textarea {
      padding: 10px 14px;
      border: 1px solid var(--border);
      border-radius: 10px;
      font-size: 15px;
      background: #fff;
    }
    textarea {
      min-height: 100px;
      resize: vertical;
    }
    .actions {
      display: flex;
      justify-content: flex-end;
      gap: 12px;
      margin-top: 24px;
    }
    .info-note {
      background: #f8fafc;
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 16px;
      font-size: 15px;
      color: var(--muted);
      line-height: 1.5;
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
        <h2>🔄 Transferir Estudiante de Empresa</h2>
        <a href="../estudiantes/view2.php?id=1" class="btn secondary">⬅ Volver al perfil</a>
      </header>

      <!-- Información actual -->
      <section class="card">
        <header>📋 Información actual</header>
        <div class="content">
          <div class="form-grid">
            <div class="form-group">
              <label>Nombre del Estudiante</label>
              <input type="text" value="Ana Rodríguez" readonly />
            </div>
            <div class="form-group">
              <label>Matrícula</label>
              <input type="text" value="A012345" readonly />
            </div>
            <div class="form-group">
              <label>Empresa actual</label>
              <input type="text" value="Casa del Barrio" readonly />
            </div>
            <div class="form-group">
              <label>Convenio actual</label>
              <input type="text" value="Convenio #2025-04 (Activo)" readonly />
            </div>
          </div>

          <div class="info-note" style="margin-top: 20px;">
            ℹ️ Este proceso creará un nuevo registro de servicio vinculado al estudiante, 
            manteniendo el historial del servicio anterior como “Concluido”.
          </div>
        </div>
      </section>

      <!-- Nuevo destino -->
      <section class="card">
        <header>🏢 Nueva empresa receptora</header>
        <div class="content">
          <form action="" method="post">
            <div class="form-grid">
              <div class="form-group">
                <label>Seleccionar nueva empresa</label>
                <select name="empresa_nueva" required>
                  <option value="">Selecciona una empresa</option>
                  <option value="1">Centro Comunitario Esperanza</option>
                  <option value="2">Ayuntamiento de Guadalajara</option>
                  <option value="3">Fundación Sembrar A.C.</option>
                </select>
              </div>

              <div class="form-group">
                <label>Seleccionar convenio asociado</label>
                <select name="convenio_nuevo" required>
                  <option value="">Selecciona un convenio</option>
                  <option value="C-001">Convenio #C-001 — Vigente hasta 2026</option>
                  <option value="C-002">Convenio #C-002 — En revisión</option>
                </select>
              </div>

              <div class="form-group">
                <label>Asignar nueva plaza</label>
                <select name="plaza_nueva" required>
                  <option value="">Selecciona una plaza</option>
                  <option value="P-1">Auxiliar Administrativo</option>
                  <option value="P-2">Soporte Técnico</option>
                  <option value="P-3">Diseñador Web</option>
                </select>
              </div>

              <div class="form-group">
                <label>Periodo de servicio</label>
                <select name="periodo" required>
                  <option value="">Selecciona periodo</option>
                  <option value="2025-1">Febrero - Julio 2025</option>
                  <option value="2025-2">Agosto 2025 - Enero 2026</option>
                </select>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px;">
              <label>Motivo del cambio</label>
              <textarea name="motivo" placeholder="Describe brevemente el motivo del cambio de empresa..." required></textarea>
            </div>

            <div class="actions">
              <button type="reset" class="btn secondary">Cancelar</button>
              <button type="submit" class="btn primary">💾 Registrar transferencia</button>
            </div>
          </form>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
