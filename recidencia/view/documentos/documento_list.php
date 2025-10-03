<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Documentos - Residencia Profesional</title>
  <link rel="stylesheet" href="../../assets/css/documentos/documentoglobalstyles.css">
</head>
<body>

  <header>
    <h1>Gestión de Documentos - Residencia Profesional</h1>
    <nav class="breadcrumb">
      <a href="../dashboard.php">Inicio</a> <span>›</span>
      <a href="../empresas/empresa_list.php">Empresas</a> <span>›</span>
      <a href="../convenios/convenio_list.php">Convenios</a> <span>›</span>
      <span>Documentos</span>
    </nav>
  </header>

  <main>

    <div class="card">
      <h2>Listado de Documentos</h2>

      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <form class="search-form" style="display: flex; gap: 10px;">
          <input type="text" placeholder="Buscar por empresa, tipo o estatus..." style="flex: 1; padding: 10px;">
          <button type="submit" class="btn btn-primary">Buscar</button>
        </form>
        <a href="documento_add.php" class="btn btn-success">+ Nuevo Documento</a>
      </div>

      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Empresa</th>
              <th>Convenio</th>
              <th>Tipo de Documento</th>
              <th>Archivo</th>
              <th>Estatus</th>
              <th>Observación</th>
              <th>Fecha de Creación</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <!-- 🧪 Ejemplos estáticos (se reemplazarán con datos dinámicos del backend) -->
            <tr>
              <td>8</td>
              <td>Casa del Barrio</td>
              <td>#1 (v1.2)</td>
              <td>Acta Constitutiva</td>
              <td><a href="/uploads/docs/technova_acta.pdf" target="_blank">📄 Ver PDF</a></td>
              <td><span class="status aprobado">Aprobado</span></td>
              <td>Acta revisada y válida</td>
              <td>2025-09-19</td>
              <td>
                <a href="documento_edit.php?id=8" class="btn btn-primary">Editar</a>
                <a href="documento_delete.php?id=8" class="btn btn-danger">Eliminar</a>
              </td>
            </tr>

            <tr>
              <td>9</td>
              <td>Casa del Barrio</td>
              <td>#1 (v1.2)</td>
              <td>INE Representante</td>
              <td><a href="/uploads/docs/technova_ine.pdf" target="_blank">📄 Ver PDF</a></td>
              <td><span class="status pendiente">Pendiente</span></td>
              <td>Falta sello notarial</td>
              <td>2025-09-19</td>
              <td>
                <a href="documento_edit.php?id=9" class="btn btn-primary">Editar</a>
                <a href="documento_delete.php?id=9" class="btn btn-danger">Eliminar</a>
              </td>
            </tr>

            <tr>
              <td>11</td>
              <td>Tequila ECT</td>
              <td>#2 (v1.0)</td>
              <td>Comprobante de domicilio</td>
              <td><a href="/uploads/docs/barberia_comprobante.pdf" target="_blank">📄 Ver PDF</a></td>
              <td><span class="status rechazado">Rechazado</span></td>
              <td>Dirección no coincide con RFC</td>
              <td>2025-09-19</td>
              <td>
                <a href="documento_edit.php?id=11" class="btn btn-primary">Editar</a>
                <a href="documento_delete.php?id=11" class="btn btn-danger">Eliminar</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </main>

</body>
</html>
