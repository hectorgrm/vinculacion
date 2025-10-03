<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Documentos - Portal Empresa</title>
  <link rel="stylesheet" href="../../assets/css/portal/portal_documentos.css">
</head>
<body>

  <header>
    <h1>📂 Documentos de Empresa</h1>
    <a href="portal_dashboard.php">← Volver al Panel</a>
  </header>

  <main>

    <section class="card">
      <h2>Documentos Asociados al Convenio</h2>
      <p>Aquí puedes revisar el estado de todos los documentos requeridos, descargar los aprobados, subir nuevos o reemplazar los rechazados.</p>

      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>Tipo de Documento</th>
              <th>Estado</th>
              <th>Observaciones</th>
              <th>Fecha</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <!-- 🧪 Ejemplos de prueba (se reemplazan con datos del backend) -->
            <tr>
              <td>Acta constitutiva</td>
              <td><span class="status aprobado">Aprobado</span></td>
              <td>Validada correctamente</td>
              <td>2025-09-12</td>
              <td>
                <a href="/uploads/docs/acta.pdf" target="_blank" class="btn btn-primary">Ver</a>
                <a href="/uploads/docs/acta.pdf" download class="btn btn-secondary">Descargar</a>
              </td>
            </tr>
            <tr>
              <td>INE Representante</td>
              <td><span class="status pendiente">Pendiente</span></td>
              <td>En revisión</td>
              <td>2025-09-15</td>
              <td>
                <a href="#" class="btn btn-upload">Subir</a>
              </td>
            </tr>
            <tr>
              <td>Comprobante de domicilio</td>
              <td><span class="status rechazado">Rechazado</span></td>
              <td>Dirección no coincide con RFC</td>
              <td>2025-09-18</td>
              <td>
                <a href="#" class="btn btn-upload">Reemplazar</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- 📤 Zona de subida de nuevo documento -->
      <div class="upload-zone">
        <p>📎 Arrastra y suelta un archivo PDF aquí o haz clic para seleccionar uno</p>
        <form action="portal_documento_upload.php" method="POST" enctype="multipart/form-data">
          <input type="file" name="documento" accept=".pdf" required>
          <br><br>
          <button type="submit" class="btn btn-upload">Subir Documento</button>
        </form>
      </div>
    </section>

  </main>

</body>
</html>
