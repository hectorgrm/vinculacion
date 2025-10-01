<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Documentos · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/documentos/globaldocumentos.css" />
</head>
<body>

  <header>
    <h1>Gestión de Documentos</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <span>Gestión de Documentos</span>
    </nav>
  </header>

  <main>

    <div class="search-bar">
      <form action="" method="get">
        <input type="text" name="q" placeholder="Buscar por estudiante, empresa o documento..." />
        <button type="submit" class="btn btn-primary">Buscar</button>
      </form>
      <a href="documento_add.php" class="btn btn-success">+ Subir Documento</a>
    </div>

    <section class="card">
      <h2>Listado de Documentos</h2>
      <table class="styled-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Estudiante</th>
            <th>Documento</th>
            <th>Empresa</th>
            <th>Convenio</th>
            <th>Estatus</th>
            <th>Fecha de creación</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>12</td>
            <td>Juan Carlos Pérez</td>
            <td>Carta de Intención</td>
            <td>Empresa XYZ</td>
            <td>Convenio 2025-01</td>
            <td><span class="status pendiente">Pendiente</span></td>
            <td>2025-09-12</td>
            <td>
              <a href="documento_view.php?id=12" class="btn btn-secondary">Ver</a>
              <a href="documento_edit.php?id=12" class="btn btn-primary">Editar</a>
            </td>
          </tr>

          <tr>
            <td>13</td>
            <td>María López</td>
            <td>Reporte Bimestral</td>
            <td>Hospital Central</td>
            <td>Convenio 2025-03</td>
            <td><span class="status aprobado">Aprobado</span></td>
            <td>2025-09-10</td>
            <td>
              <a href="documento_view.php?id=13" class="btn btn-secondary">Ver</a>
              <a href="documento_edit.php?id=13" class="btn btn-primary">Editar</a>
            </td>
          </tr>

          <tr>
            <td>14</td>
            <td>Carlos García</td>
            <td>Autoevaluación</td>
            <td>Fundación ABC</td>
            <td>Convenio 2025-05</td>
            <td><span class="status rechazado">Rechazado</span></td>
            <td>2025-09-08</td>
            <td>
              <a href="documento_view.php?id=14" class="btn btn-secondary">Ver</a>
              <a href="documento_edit.php?id=14" class="btn btn-primary">Editar</a>
            </td>
          </tr>
        </tbody>
      </table>
    </section>

  </main>

</body>
</html>
