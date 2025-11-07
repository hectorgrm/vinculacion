<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Estudiante · Residencia Profesional</title>

  <link rel="stylesheet" href="../../assets/css/global.css" />
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <link rel="stylesheet" href="../../assets/css/modules/estudiante.css" />

  <style>
    body {
      font-family: "Inter", sans-serif;
      background: #f6f8fa;
      margin: 0;
      color: #2c3e50;
    }
    .main {
      max-width: 1100px;
      margin: 2rem auto;
      padding: 0 1.5rem;
    }
    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }
    .btn {
      background: #0055aa;
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      text-decoration: none;
      font-size: 0.9rem;
    }
    .btn.secondary {
      background: #7f8c8d;
    }
    .card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
      margin-bottom: 1.8rem;
      overflow: hidden;
    }
    .card header {
      background: #f1f3f5;
      padding: 0.8rem 1.2rem;
      font-weight: 600;
      border-bottom: 1px solid #e5e7ea;
    }
    .content {
      padding: 1.2rem;
    }
    .form-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 1.2rem;
    }
    .form-group {
      display: flex;
      flex-direction: column;
    }
    .form-group label {
      font-weight: 600;
      margin-bottom: 0.3rem;
      color: #2c3e50;
    }
    .form-group input,
    .form-group select {
      padding: 8px 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 0.95rem;
    }
    .form-group input:focus,
    .form-group select:focus {
      border-color: #0074e4;
      outline: none;
    }
    .form-actions {
      grid-column: 1 / -1;
      display: flex;
      justify-content: flex-end;
      gap: 0.8rem;
      margin-top: 1.2rem;
    }
    .successbox,
    .dangerbox {
      padding: 0.9rem 1.2rem;
      border-radius: 6px;
      margin-bottom: 1rem;
      font-size: 0.95rem;
    }
    .successbox {
      background: #eaf8ee;
      border: 1px solid #27ae60;
      color: #2e7d32;
    }
    .dangerbox {
      background: #fcebea;
      border: 1px solid #e74c3c;
      color: #c0392b;
    }
    .foot {
      text-align: center;
      color: #888;
      margin-top: 2rem;
      font-size: 0.9rem;
    }
  </style>
</head>

<body>
 <div class="app">  
         <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

  <main class="main">

    <!-- Encabezado -->
    <header class="topbar">
      <div>
        <h2>✏️ Editar Estudiante</h2>
        <p class="subtitle">Modifica los datos de un estudiante y su asignación a empresa y convenio.</p>
      </div>
      <div class="actions">
        <a href="estudiante_view.php" class="btn secondary">← Cancelar</a>
      </div>
    </header>

    <!-- Mensajes de estado -->
    <div class="successbox" style="display:none;">
      ✅ Cambios guardados correctamente.
    </div>
    <div class="dangerbox" style="display:none;">
      ⚠️ No se pudo actualizar el registro. Verifique los datos.
    </div>

    <!-- Formulario -->
    <section class="card">
      <header>Datos generales del estudiante</header>
      <div class="content">
        <form class="form-grid">

          <!-- Datos personales -->
          <div class="form-group">
            <label for="nombre">Nombre(s)</label>
            <input type="text" id="nombre" name="nombre" value="Juan Carlos" required>
          </div>

          <div class="form-group">
            <label for="apellido_paterno">Apellido paterno</label>
            <input type="text" id="apellido_paterno" name="apellido_paterno" value="Pérez" required>
          </div>

          <div class="form-group">
            <label for="apellido_materno">Apellido materno</label>
            <input type="text" id="apellido_materno" name="apellido_materno" value="López">
          </div>

          <div class="form-group">
            <label for="matricula">Matrícula</label>
            <input type="text" id="matricula" name="matricula" value="20230145" readonly>
          </div>

          <div class="form-group">
            <label for="carrera">Carrera</label>
            <input type="text" id="carrera" name="carrera" value="Ingeniería en Informática" required>
          </div>

          <div class="form-group">
            <label for="correo_institucional">Correo institucional</label>
            <input type="email" id="correo_institucional" name="correo_institucional" value="juan.perez@universidad.mx" required>
          </div>

          <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" id="telefono" name="telefono" value="3312345678">
          </div>

          <!-- Empresa -->
          <div class="form-group">
            <label for="empresa_id">Empresa</label>
            <select id="empresa_id" name="empresa_id" required>
              <option value="">Selecciona una empresa...</option>
              <option value="1" selected>Barbería Gómez</option>
              <option value="2">Homero Burgers</option>
              <option value="3">Estética Lupita</option>
            </select>
          </div>

          <!-- Convenio -->
          <div class="form-group">
            <label for="convenio_id">Convenio</label>
            <select id="convenio_id" name="convenio_id">
              <option value="">Sin asignar</option>
              <option value="1" selected>CVN-2025-001</option>
              <option value="2">CVN-2025-002</option>
              <option value="3">CVN-2025-003</option>
            </select>
          </div>

          <!-- Estatus -->
          <div class="form-group">
            <label for="estatus">Estatus</label>
            <select id="estatus" name="estatus">
              <option value="Activo" selected>Activo</option>
              <option value="Finalizado">Finalizado</option>
              <option value="Inactivo">Inactivo</option>
            </select>
          </div>

          <!-- Botones -->
          <div class="form-actions">
            <button type="submit" class="btn primary">💾 Guardar cambios</button>
            <a href="rp_estudiante_view.php" class="btn secondary">Cancelar</a>
          </div>

        </form>
      </div>
    </section>

    <p class="foot">Área de Vinculación · Residencias Profesionales</p>
  </main>
</div>
</body>
</html>
