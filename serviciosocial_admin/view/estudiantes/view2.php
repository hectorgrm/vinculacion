<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión Integral del Estudiante · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/estudiante/estudianteview.css" />
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <style>
    .status { font-weight: bold; padding: 4px 8px; border-radius: 6px; }
    .status.pendiente { color: #b00; background: #ffd9d9; }
    .status.activo { color: #0a0; background: #d9ffd9; }
    .status.aprobado { color: #007bff; background: #d9e8ff; }
    .status.cancelado { color: #555; background: #eee; }
    .card .content p { margin: 5px 0; }
    .info-row { margin-bottom: 6px; }
  </style>
</head>

<body>
  <div class="app">
    <!-- Sidebar -->
    <!-- (Incluye aquí tu sidebar si aplica) -->
         <?php include __DIR__ . '/../../layout/sidebar.php'; ?>


    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <h2>👤 Gestión Integral del Estudiante</h2>
        <a href="progreso.php?id=1" class="btn primary">📊 Ver Progreso</a>
        <a href="list.php" class="btn secondary">⬅ Volver al listado</a>
      </header>

      <!-- 🧑‍🎓 Información básica -->
      <section class="card">
        <header>📄 Información del Estudiante</header>
        <div class="content student-info">
          <div class="info-row"><strong>Nombre:</strong> <span>Ana Rodríguez</span></div>
          <div class="info-row"><strong>Matrícula:</strong> <span>A012345</span></div>
          <div class="info-row"><strong>Carrera:</strong> <span>Ingeniería en Informática</span></div>
          <div class="info-row"><strong>Correo:</strong> <span>ana.rodriguez@alumno.edu.mx</span></div>
          <div class="info-row"><strong>Teléfono:</strong> <span>3310000001</span></div>
          <div class="info-row"><strong>Semestre:</strong> <span>8</span></div>
        </div>
      </section>

      <!-- 🏢 Empresa -->
      <section class="card">
        <header>🏢 Empresa Asignada</header>
        <div class="content">
          <p><strong>Nombre:</strong> Casa del Barrio</p>
          <p><strong>Contacto:</strong> José Manuel Velador</p>
          <p><strong>Correo:</strong> contacto@casadelbarrio.mx</p>
          <p><strong>Teléfono:</strong> (33) 1234 5678</p>
          <p><strong>Estado:</strong> <span class="status activo">✅ Activa</span></p>
          <div class="actions">
  <a href="../servicios/transferir.php?estudiante=1" class="btn secondary">
    🔄 Solicitar cambio de empresa
  </a>
</div>

        </div>
      </section>

      <!-- 📜 Convenio -->
      <section class="card">
        <header>📜 Convenio Asociado</header>
        <div class="content">
          <p><strong>Estado:</strong> <span class="status aprobado">📜 Vigente</span></p>
          <p><strong>Vigencia:</strong> 01 julio 2025 – 30 junio 2026</p>
          <p><strong>Versión:</strong> v1.2</p>
          <div class="actions">
  <a href="../asignaciones/edit.php?servicio=1" class="btn secondary">
    🔄 Cambiar convenio
  </a>
</div>

        </div>
      </section>

      <!-- 📍 Plaza -->
      <section class="card">
        <header>📍 Plaza Asignada</header>
        <div class="content">
          <p><strong>Nombre:</strong> Auxiliar de Laboratorio</p>
          <p><strong>Ubicación:</strong> Campus Central, Edificio B</p>
          <p><strong>Periodo:</strong> 01 feb – 31 jul 2025</p>
          <p><strong>Responsable:</strong> Dra. Laura Sánchez</p>
          <p><strong>Teléfono:</strong> 555-123-4567</p>
          <p><strong>Estado:</strong> <span class="status activo">✅ Activa</span></p>
        </div>
      </section>

      <!-- 🗓️ Periodo -->
      <section class="card">
        <header>🗓️ Periodo de Servicio</header>
        <div class="content">
          <p><strong>Periodo:</strong> #1</p>
          <p><strong>Estado:</strong> <span class="status aprobado">🕒 En revisión</span></p>
          <p><strong>Fecha de inicio:</strong> 01 febrero 2025</p>
          <p><strong>Fecha de fin:</strong> 31 julio 2025</p>
        </div>
      </section>

      <!-- 📄 Documentos -->
      <section class="card">
        <header>📄 Documentos Asignados</header>
        <div class="content">
          <p><strong>Progreso:</strong> 3 / 5 documentos aprobados</p>
          <ul>
            <li>✅ Carta de presentación — Aprobado</li>
            <li>⚠️ Oficio de aceptación — Rechazado (falta sello)</li>
            <li>⏳ Plan de trabajo — Pendiente</li>
            <li>⏳ Reporte parcial — Pendiente</li>
            <li>⏳ Carta de término — Pendiente</li>
          </ul>
        </div>
      </section>

      <!-- 💼 Servicio -->
      <section class="card">
        <header>💼 Proyecto / Servicio</header>
        <div class="content">
          <p><strong>Proyecto:</strong> Desarrollo de Sistema de Control de Documentos para Dependencias Gubernamentales</p>
          <p><strong>Estado:</strong> <span class="status activo">🟢 Activo</span></p>
          <p><strong>Horas acumuladas:</strong> 120 / 480 hrs</p>
          <p><strong>Asesor interno:</strong> Ing. Fernando López</p>
        </div>
      </section>

      <!-- 📝 Observaciones -->
      <section class="card">
        <header>📝 Observaciones</header>
        <div class="content">
          <p>El estudiante ha mostrado excelente desempeño en el área asignada. Pendiente entregar reporte intermedio.</p>
          <div class="actions">
            <a href="edit.php?id=1" class="btn secondary">✏️ Agregar Observaciones</a>
          </div>
        </div>
      </section>

      <!-- 🔚 Acciones finales -->
      <div class="actions final-actions">
        <a href="edit.php?id=1" class="btn primary">✏️ Editar Información</a>
        <a href="delete.php?id=1" class="btn danger">🗑️ Eliminar Estudiante</a>
      </div>
    </main>
  </div>
</body>
</html>
