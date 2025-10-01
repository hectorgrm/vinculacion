<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detalle del Documento · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/documentos/globaldocumentos.css" />
</head>
<body>

  <header>
    <h1>Detalle del Documento</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="documentos_list.php">Gestión de Documentos</a>
      <span>›</span>
      <span>Detalle</span>
    </nav>
  </header>

  <main>
    <a href="documentos_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

    <!-- 📄 Información general del documento -->
    <section class="card">
      <h2>Información del Documento</h2>

      <div class="grid cols-2">
        <div class="field">
          <label>ID del Documento</label>
          <p>12</p>
        </div>

        <div class="field">
          <label>Nombre del Documento</label>
          <p>Carta de Intención</p>
        </div>

        <div class="field">
          <label>Tipo</label>
          <p>Convenio / Empresa</p>
        </div>

        <div class="field">
          <label>Fecha de creación</label>
          <p>2025-09-12</p>
        </div>

        <div class="field">
          <label>Estatus actual</label>
          <p><span class="status pendiente">Pendiente</span></p>
        </div>

        <div class="field">
          <label>Archivo</label>
          <p>
            <a href="../../uploads/documentos/carta_intencion.pdf" target="_blank" class="btn btn-primary">📄 Ver / Descargar</a>
          </p>
        </div>
      </div>
    </section>

    <!-- 🧑‍🎓 Información del estudiante -->
    <section class="card">
      <h2>Información del Estudiante</h2>

      <div class="grid cols-2">
        <div class="field">
          <label>Nombre</label>
          <p>Juan Carlos Pérez López</p>
        </div>
        <div class="field">
          <label>Matrícula</label>
          <p>20214567</p>
        </div>
        <div class="field">
          <label>Carrera</label>
          <p>Ingeniería en Informática</p>
        </div>
        <div class="field">
          <label>Correo</label>
          <p>juan.perez@example.com</p>
        </div>
      </div>
    </section>

    <!-- 🏢 Información de la empresa -->
    <section class="card">
      <h2>Información de la Empresa / Convenio</h2>

      <div class="grid cols-2">
        <div class="field">
          <label>Empresa</label>
          <p>Tech Solutions S.A. de C.V.</p>
        </div>
        <div class="field">
          <label>Convenio</label>
          <p>Convenio 2025-01</p>
        </div>
        <div class="field">
          <label>Contacto</label>
          <p>Ing. Luis Hernández</p>
        </div>
        <div class="field">
          <label>Correo</label>
          <p>lhernandez@techsolutions.com</p>
        </div>
      </div>
    </section>

    <!-- 📝 Observaciones -->
    <section class="card">
      <h2>Observaciones / Notas</h2>
      <p>
        El documento aún no ha sido revisado. Se recomienda verificar que todos los datos estén completos y sean legibles antes de aprobar.
      </p>
    </section>

    <!-- ✅ Acciones administrativas -->
    <div class="actions">
      <a href="documento_edit.php?id=12" class="btn btn-primary">✏️ Editar</a>
      <a href="documento_aprobar.php?id=12" class="btn btn-success">✅ Aprobar</a>
      <a href="documento_rechazar.php?id=12" class="btn btn-danger">❌ Rechazar</a>
    </div>

  </main>

</body>
</html>
