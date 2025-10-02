<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Empresas · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/empresas/empresaglobalstyles.css" />
</head>
<body>

  <!-- ===== HEADER ===== -->
  <header>
    <h1>Gestión de Empresas</h1>
    <p>Administra las empresas registradas en el sistema de Servicio Social</p>
  </header>

  <div class="container">

    <!-- ===== BARRA DE ACCIONES ===== -->
    <div class="search-bar">
      <form action="#" method="get">
        <input type="text" placeholder="Buscar empresa por nombre..." />
        <button type="submit" class="btn btn-primary">Buscar</button>
      </form>
      <a href="empresa_add.html" class="btn btn-success">+ Nueva Empresa</a>
    </div>

    <!-- ===== TABLA ===== -->
    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Contacto</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td data-label="ID">1</td>
            <td data-label="Nombre">Universidad Tecnológica del Centro</td>
            <td data-label="Contacto">Dra. María López</td>
            <td data-label="Email">maria.lopez@utc.edu</td>
            <td data-label="Teléfono">(33) 1234 5678</td>
            <td data-label="Estado"><span class="badge badge-activo">Activo</span></td>
            <td data-label="Acciones" class="actions">
              <a href="empresa_edit.html" class="btn btn-warning btn-sm">Editar</a>
              <a href="empresa_delete.html" class="btn btn-danger btn-sm">Eliminar</a>
            </td>
          </tr>
          <tr>
            <td data-label="ID">2</td>
            <td data-label="Nombre">Hospital General San José</td>
            <td data-label="Contacto">Dr. Juan Pérez</td>
            <td data-label="Email">juan.perez@hgsj.mx</td>
            <td data-label="Teléfono">(55) 9876 5432</td>
            <td data-label="Estado"><span class="badge badge-inactivo">Inactivo</span></td>
            <td data-label="Acciones" class="actions">
              <a href="empresa_edit.html" class="btn btn-warning btn-sm">Editar</a>
              <a href="empresa_delete.html" class="btn btn-danger btn-sm">Eliminar</a>
            </td>
          </tr>
          <tr>
            <td data-label="ID">3</td>
            <td data-label="Nombre">Biblioteca Municipal Central</td>
            <td data-label="Contacto">Lic. Ana Torres</td>
            <td data-label="Email">ana.torres@biblio.gob.mx</td>
            <td data-label="Teléfono">(55) 4567 8901</td>
            <td data-label="Estado"><span class="badge badge-activo">Activo</span></td>
            <td data-label="Acciones" class="actions">
              <a href="empresa_edit.html" class="btn btn-warning btn-sm">Editar</a>
              <a href="empresa_delete.html" class="btn btn-danger btn-sm">Eliminar</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>

</body>
</html>
